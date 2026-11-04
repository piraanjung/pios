<?php

namespace App\Console\Commands;

use App\Models\Admin\Organization;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class TenantSyncReference extends Command
{
    protected $signature = 'tenant:sync-reference
                            {org_id? : ID ขององค์กร (ไม่ระบุ = ทุกองค์กรที่แยก Database แล้ว)}
                            {--dry-run : แสดงจำนวนแถวที่จะ sync โดยไม่เขียนข้อมูล}';

    protected $description = 'คัดลอกข้อมูลอ้างอิงจากฐานกลาง (users, zones, subzones, staffs, roles, ที่ตั้ง ฯลฯ) ไปยัง Database ของแต่ละองค์กร (upsert ไม่ลบข้อมูลเดิม)';

    /** ตารางที่คัดลอกทั้งตาราง */
    protected array $sharedTables = [
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
        'system_modules',
    ];

    public function handle(): int
    {
        $central = Config::get('database.connections.envsogo_main.database');
        $query = Organization::on('envsogo_main')->whereNotNull('org_database');
        if ($this->argument('org_id')) {
            $query->where('id', $this->argument('org_id'));
        }

        $orgs = $query->get()->filter(fn ($o) => !in_array(trim($o->org_database), [$central, 'envsogo_main', 'envsogo_super_admin', 'mysql', ''], true));
        if ($orgs->isEmpty()) {
            $this->warn('ไม่พบองค์กรที่แยก Database');
            return self::SUCCESS;
        }

        foreach ($orgs as $org) {
            $this->info("== [{$org->id}] {$org->org_name} -> {$org->org_database}");
            Config::set('database.connections.tenant.database', $org->org_database);
            DB::purge('tenant');
            try {
                $tenant = DB::connection('tenant');
                $tenant->getPdo();
            } catch (\Throwable $e) {
                $this->error('  เชื่อมต่อไม่ได้: ' . $e->getMessage());
                continue;
            }
            $this->syncOrg($org, $central, $org->org_database, $tenant);
        }

        return self::SUCCESS;
    }

    protected function syncOrg($org, string $central, string $target, $tenant): void
    {
        $q = fn (string $n) => '`' . str_replace('`', '``', $n) . '`';
        $tenant->statement('SET FOREIGN_KEY_CHECKS=0');

        $plan = [];
        foreach ($this->sharedTables as $t) {
            $plan[$t] = ['where' => '1=1', 'bind' => []];
        }
        $plan['users'] = ['where' => '(org_id_fk = ? OR org_id_fk IS NULL)', 'bind' => [$org->id]];
        // รวมโซน/ซอยที่ผู้ใช้ขององค์กรอ้างถึง แม้จะสังกัด org อื่น เพื่อไม่ให้ FK ของ users พัง
        $userRef = "SELECT %s FROM {$q($central)}.`users` WHERE (org_id_fk = ? OR org_id_fk IS NULL) AND %s IS NOT NULL";
        $plan['zones'] = [
            'where' => 'org_id_fk = ? OR id IN (' . sprintf($userRef, 'zone_id', 'zone_id') . ')',
            'bind' => [$org->id, $org->id],
        ];
        $plan['subzones'] = [
            'where' => 'org_id_fk = ? OR id IN (' . sprintf($userRef, 'subzone_id', 'subzone_id') . ')',
            'bind' => [$org->id, $org->id],
        ];
        $plan['staffs'] = [
            'where' => "(org_id_fk = ? OR id IN (SELECT twman_id FROM {$q($central)}.`undertaker_subzones`))",
            'bind' => [$org->id],
        ];
        $plan['undertaker_subzones'] = ['where' => '1=1', 'bind' => []];

        foreach ($plan as $table => $p) {
            $centralCols = array_map(fn ($c) => $c->Field, DB::connection('envsogo_main')->select("SHOW COLUMNS FROM {$q($table)}"));
            if (empty($centralCols)) {
                continue;
            }
            try {
                $tenantCols = array_map(fn ($c) => $c->Field, $tenant->select("SHOW COLUMNS FROM {$q($table)}"));
            } catch (\Throwable $e) {
                $this->warn("  ข้าม {$table}: ไม่มีตารางในฐาน {$target} (รัน tenant:migrate หรือ tenant:export-db ก่อน)");
                continue;
            }

            $cols = array_values(array_intersect($centralCols, $tenantCols));
            $list = implode(',', array_map($q, $cols));
            $update = implode(',', array_map(fn ($c) => "{$q($c)}=VALUES({$q($c)})", $cols));
            $where = $p['where'];
            // ตารางที่ไม่มี org_id_fk ให้ใช้เงื่อนไขนี้ไม่ได้ จึงตัดทิ้ง
            if (!in_array('org_id_fk', $centralCols, true)) {
                $where = $table === 'staffs' ? $where : '1=1';
                $p['bind'] = $where === '1=1' ? [] : $p['bind'];
            }

            $count = DB::connection('envsogo_main')->selectOne("SELECT COUNT(*) n FROM {$q($table)} WHERE {$where}", $p['bind'])->n;
            if ($this->option('dry-run')) {
                $this->line("  {$table}: {$count} แถว (dry-run)");
                continue;
            }

            $tenant->statement(
                "INSERT INTO {$q($target)}.{$q($table)} ({$list}) SELECT {$list} FROM {$q($central)}.{$q($table)} WHERE {$where} ON DUPLICATE KEY UPDATE {$update}",
                $p['bind']
            );
            $this->line("  {$table}: sync {$count} แถว");
        }

        $tenant->statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
