<?php

namespace App\Console\Commands;

use App\Models\Admin\Organization;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class TenantMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:migrate
                            {org_id? : รหัส ID ขององค์กร (หากไม่ระบุจะรันกับทุกองค์กรที่มี org_database)}
                            {--force : บังคับรันบน Production}
                            {--path=* : ระบุ migration path เฉพาะที่ต้องการรัน}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'รัน Database Migrations ไปยังฐานข้อมูลของทุกองค์กรที่แยก Database แล้ว';

    public function handle(): int
    {
        $orgId = $this->argument('org_id');
        $centralDb = Config::get('database.connections.envsogo_main.database');

        $query = Organization::on('envsogo_main')
            ->whereNotNull('org_database')
            ->where('org_database', '!=', '')
            ->where('org_database', '!=', $centralDb);

        if ($orgId) {
            $query->where('id', $orgId);
        }

        $organizations = $query->get();

        if ($organizations->isEmpty()) {
            $this->warn('ไม่พบองค์กรที่ตั้งค่า org_database แยกไว้');
            return self::SUCCESS;
        }

        foreach ($organizations as $org) {
            $this->info("กำลังรัน Migration สำหรับ [{$org->id}] {$org->org_name} (DB: {$org->org_database})...");

            Config::set('database.connections.tenant.database', $org->org_database);
            DB::purge('tenant');

            $params = [
                '--database' => 'tenant',
            ];

            if ($this->option('path')) {
                $params['--path'] = $this->option('path');
            }

            if ($this->option('force')) {
                $params['--force'] = true;
            }

            Artisan::call('migrate', $params, $this->getOutput());
        }

        $this->info('รัน Migration ครบทุกองค์กรเรียบร้อยแล้ว!');
        return self::SUCCESS;
    }
}

