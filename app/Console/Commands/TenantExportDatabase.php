<?php

namespace App\Console\Commands;

use App\Models\Admin\Organization;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class TenantExportDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:export-db
                            {org_id? : รหัส ID ขององค์กรในตาราง organizations (หากไม่ระบุจะแยกทุกองค์กร)}
                            {--db= : ชื่อ Database ที่ต้องการใช้ (หากไม่ระบุจะใช้ค่าใน org_database หรือ envsogo_org_{id})}
                            {--fresh : ล้างตารางใน Tenant DB ใหม่ทั้งหมดก่อนคัดลอกข้อมูล}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'สร้าง Database แยกสำหรับองค์กร คัดลอกโครงสร้างตาราง และดึงเฉพาะข้อมูลของ org_id_fk นั้นๆ ออกมาจากฐานข้อมูลหลัก';

    /**
     * ตารางข้อมูลกลาง (Master Data) ที่ต้องคัดลอกไปทุกองค์กรเพื่อให้ระบบอ้างอิงได้ครบ
     */
    protected array $sharedTables = [
        'migrations',
        'organizations',
        'organization_types',
        'provinces',
        'districts',
        'tambons',
        'roles',
        'permissions',
        'role_has_permissions',
        'model_has_roles',
        'model_has_permissions',
        'emission_factors',
        'system_modules',
    ];

    public function handle(): int
    {
        $orgId = $this->argument('org_id');
        $customDb = $this->option('db');

        if ($customDb && !$orgId) {
            $this->error('หากใช้ตัวเลือก --db กรุณาระบุ org_id ขององค์กรที่ต้องการด้วยครับ');
            return self::FAILURE;
        }

        $query = Organization::on('envsogo_main');
        if ($orgId) {
            $query->where('id', $orgId);
        }

        $organizations = $query->get();

        if ($organizations->isEmpty()) {
            $this->error('ไม่พบข้อมูลองค์กรที่ระบุในตาราง organizations');
            return self::FAILURE;
        }

        $centralDb = Config::get('database.connections.envsogo_main.database');

        foreach ($organizations as $org) {
            $targetDb = $customDb ?: ($org->org_database ?: 'envsogo_org_' . $org->id);

            if ($targetDb === $centralDb) {
                $this->warn("ข้ามองค์กร [{$org->id}] {$org->org_name} เนื่องจากชื่อ Database ตรงกับฐานข้อมูลหลัก ({$centralDb})");
                continue;
            }

            $this->info("=================================================================");
            $this->info("กำลังแยกฐานข้อมูลสำหรับองค์กร: [{$org->id}] {$org->org_name}");
            $this->info("Database ปลายทาง: {$targetDb}");
            $this->info("=================================================================");

            $this->exportOrganizationDatabase($org, $centralDb, $targetDb);
        }

        $this->newLine();
        $this->info('ดำเนินการแยกฐานข้อมูลองค์กรเสร็จสมบูรณ์!');
        return self::SUCCESS;
    }

    protected function exportOrganizationDatabase(Organization $org, string $centralDb, string $targetDb): void
    {
        $mainConn = DB::connection('envsogo_main');

        // 1. สร้าง Database ใหม่ถ้ายังไม่มี
        $safeDbName = str_replace('`', '``', $targetDb);
        $mainConn->statement("CREATE DATABASE IF NOT EXISTS `{$safeDbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        // 2. ตั้งค่า Connection 'tenant' ไปยัง Database ปลายทาง
        Config::set('database.connections.tenant.database', $targetDb);
        DB::purge('tenant');
        $tenantConn = DB::connection('tenant');

        $mainConn->statement('SET FOREIGN_KEY_CHECKS=0');
        $tenantConn->statement('SET FOREIGN_KEY_CHECKS=0');

        // 3. ดึงรายชื่อตารางทั้งหมดจากฐานข้อมูลกลาง
        $tablesResult = $mainConn->select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"');
        $tables = [];
        foreach ($tablesResult as $row) {
            $values = array_values((array) $row);
            $tables[] = $values[0];
        }

        // เตรียมรายชื่อ user_id ขององค์กรนี้ (รวม Super Admin ที่มี org_id_fk IS NULL)
        $orgUserIds = $mainConn->table('users')
            ->where('org_id_fk', $org->id)
            ->orWhereNull('org_id_fk')
            ->pluck('id')
            ->toArray();

        foreach ($tables as $table) {
            $safeTable = str_replace('`', '``', $table);

            if ($this->option('fresh')) {
                $tenantConn->statement("DROP TABLE IF EXISTS `{$safeTable}`");
            }

            // ตรวจสอบว่ามีตารางใน Tenant DB หรือยัง
            $tableExists = $tenantConn->select("SHOW TABLES LIKE '{$table}'");
            if (empty($tableExists)) {
                $createTableResult = $mainConn->select("SHOW CREATE TABLE `{$safeTable}`");
                $createSql = ((array) $createTableResult[0])['Create Table'] ?? null;
                if ($createSql) {
                    $tenantConn->statement($createSql);
                }
            } else {
                $tenantConn->statement("TRUNCATE TABLE `{$safeTable}`");
            }

            // ตรวจสอบคอลัมน์ในตาราง
            $columnsResult = $mainConn->select("SHOW COLUMNS FROM `{$safeTable}`");
            $columns = array_map(fn($col) => ((array) $col)['Field'], $columnsResult);

            // คัดกรองข้อมูลที่จะคัดลอกไปใส่ใน Tenant DB
            if (in_array($table, $this->sharedTables, true)) {
                // ตารางส่วนกลาง คัดลอกทั้งหมด
                $tenantConn->statement("INSERT INTO `{$safeDbName}`.`{$safeTable}` SELECT * FROM `{$centralDb}`.`{$safeTable}`");
                $this->line("  [Shared] คัดลอกตาราง {$table} เรียบร้อย");
            } elseif ($table === 'users') {
                // ตาราง users คัดลอกเฉพาะคนใน org_id_fk นั้น และบัญชีกลาง (org_id_fk IS NULL)
                $tenantConn->statement(
                    "INSERT INTO `{$safeDbName}`.`{$safeTable}` SELECT * FROM `{$centralDb}`.`{$safeTable}` WHERE `org_id_fk` = ? OR `org_id_fk` IS NULL",
                    [$org->id]
                );
                $this->line("  [Tenant] คัดลอกตาราง users เฉพาะขององค์กร #{$org->id}");
            } elseif (in_array('org_id_fk', $columns, true)) {
                // ตารางที่มี org_id_fk คัดลอกเฉพาะข้อมูลขององค์กรนี้
                $tenantConn->statement(
                    "INSERT INTO `{$safeDbName}`.`{$safeTable}` SELECT * FROM `{$centralDb}`.`{$safeTable}` WHERE `org_id_fk` = ?",
                    [$org->id]
                );
                $this->line("  [Tenant] คัดลอกตาราง {$table} (where org_id_fk = {$org->id})");
            } elseif (in_array('organization_id', $columns, true)) {
                $tenantConn->statement(
                    "INSERT INTO `{$safeDbName}`.`{$safeTable}` SELECT * FROM `{$centralDb}`.`{$safeTable}` WHERE `organization_id` = ?",
                    [$org->id]
                );
                $this->line("  [Tenant] คัดลอกตาราง {$table} (where organization_id = {$org->id})");
            } elseif (in_array('user_id', $columns, true) && !empty($orgUserIds)) {
                // ตารางลูกที่อ้างอิงผ่าน user_id ของสมาชิกในองค์กรนั้น
                $tenantConn->statement(
                    "INSERT INTO `{$safeDbName}`.`{$safeTable}` SELECT * FROM `{$centralDb}`.`{$safeTable}` WHERE `user_id` IN (SELECT `id` FROM `{$centralDb}`.`users` WHERE `org_id_fk` = ? OR `org_id_fk` IS NULL)",
                    [$org->id]
                );
                $this->line("  [Child]  คัดลอกตาราง {$table} (ตาม user_id ขององค์กร #{$org->id})");
            } else {
                // ตารางย่อยอื่นๆ (เช่น detail / tier / config) คัดลอกโครงสร้างและข้อมูลอ้างอิง
                $tenantConn->statement("INSERT INTO `{$safeDbName}`.`{$safeTable}` SELECT * FROM `{$centralDb}`.`{$safeTable}`");
                $this->line("  [Lookup] คัดลอกตาราง {$table}");
            }
        }

        $tenantConn->statement('SET FOREIGN_KEY_CHECKS=1');
        $mainConn->statement('SET FOREIGN_KEY_CHECKS=1');

        // 4. บันทึกชื่อ org_database ลงในตาราง organizations บน Central DB
        if ($org->org_database !== $targetDb) {
            $org->org_database = $targetDb;
            $org->save();
            $this->info("-> อัปเดต organizations.org_database = '{$targetDb}' สำเร็จ");
        }
    }
}

