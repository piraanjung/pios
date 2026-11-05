<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'annual_trashs',
        'annual_trash_payments',
        'annual_trash_payrate_permonth',
    ];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'org_id_fk')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->foreignId('org_id_fk')
                        ->nullable()
                        ->after('id')
                        ->constrained('organizations')
                        ->nullOnDelete();
                });
            }
        }

        if (DB::getDriverName() !== 'sqlite' && Schema::hasTable('annual_trashs') && Schema::hasTable('users')) {
            DB::statement(<<<'SQL'
                UPDATE annual_trashs AS bins
                INNER JOIN users AS users ON users.id = bins.user_id
                SET bins.org_id_fk = users.org_id_fk
                WHERE bins.org_id_fk IS NULL AND users.org_id_fk IS NOT NULL
            SQL);
        }

        if (DB::getDriverName() !== 'sqlite' && Schema::hasTable('annual_trash_payments') && Schema::hasTable('annual_trash_subscriptions') && Schema::hasColumn('annual_trash_subscriptions', 'org_id_fk')) {
            DB::statement(<<<'SQL'
                UPDATE annual_trash_payments AS payments
                INNER JOIN annual_trash_subscriptions AS subscriptions
                    ON subscriptions.id = payments.wbs_id
                SET payments.org_id_fk = subscriptions.org_id_fk
                WHERE payments.org_id_fk IS NULL AND subscriptions.org_id_fk IS NOT NULL
            SQL);
        }

        if (DB::getDriverName() !== 'sqlite' && Schema::hasTable('annual_trash_payrate_permonth') && Schema::hasTable('budget_year') && Schema::hasColumn('budget_year', 'org_id_fk')) {
            DB::statement(<<<'SQL'
                UPDATE annual_trash_payrate_permonth AS payrates
                INNER JOIN budget_year AS years ON years.id = payrates.budgetyear_idfk
                SET payrates.org_id_fk = years.org_id_fk
                WHERE payrates.org_id_fk IS NULL AND years.org_id_fk IS NOT NULL
            SQL);
        }

        if (DB::getDriverName() !== 'sqlite' && Schema::hasTable('annual_trash_members') && Schema::hasColumn('annual_trash_members', 'org_id_fk') && Schema::hasTable('users')) {
            DB::statement(<<<'SQL'
                UPDATE annual_trash_members AS members
                INNER JOIN users AS users ON users.id = members.user_id
                SET members.org_id_fk = users.org_id_fk
                WHERE members.org_id_fk IS NULL AND users.org_id_fk IS NOT NULL
            SQL);
        }

        if (DB::getDriverName() !== 'sqlite' && Schema::hasTable('annual_trash_subscriptions') && Schema::hasColumn('annual_trash_subscriptions', 'org_id_fk') && Schema::hasTable('users')) {
            DB::statement(<<<'SQL'
                UPDATE annual_trash_subscriptions AS subscriptions
                INNER JOIN users AS users ON users.id = subscriptions.user_id
                SET subscriptions.org_id_fk = users.org_id_fk
                WHERE subscriptions.org_id_fk IS NULL AND users.org_id_fk IS NOT NULL
            SQL);
        }
    }

    public function down(): void
    {
        foreach (array_reverse($this->tables) as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'org_id_fk')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropForeign(['org_id_fk']);
                    $table->dropColumn('org_id_fk');
                });
            }
        }
    }
};