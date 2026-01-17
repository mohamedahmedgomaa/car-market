<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigratePgsqlToSqlite extends Command
{
    protected $signature = 'db:migrate-pgsql-to-sqlite';
    protected $description = 'Migrate data from PostgreSQL to SQLite';

    public function handle()
    {
        Schema::disableForeignKeyConstraints();

        $tables = DB::connection('pgsql')->select("
            SELECT tablename
            FROM pg_tables
            WHERE schemaname = 'public'
        ");

        foreach ($tables as $table) {
            $tableName = $table->tablename;

            if (in_array($tableName, ['migrations', 'failed_jobs'])) {
                continue;
            }

            $rows = DB::connection('pgsql')->table($tableName)->get();

            if ($rows->count()) {
                DB::connection('sqlite')
                    ->table($tableName)
                    ->insert(json_decode(json_encode($rows), true));
            }

            $this->info("✔ migrated: {$tableName}");
        }

        Schema::enableForeignKeyConstraints();

        $this->info('🎉 Migration finished successfully');
    }
}
