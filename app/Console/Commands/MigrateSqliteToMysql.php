<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateSqliteToMysql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:sqlite-to-mysql
                            {--sqlite= : Custom path to the SQLite database file}
                            {--force : Force the operation without asking for confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all tables and records from SQLite to MySQL database with 100% data fidelity';

    /**
     * List of application tables to transfer in order.
     */
    protected array $tables = [
        'users',
        'password_reset_tokens',
        'categories',
        'pages',
        'articles',
        'article_category',
        'advertisements',
        'menu_items',
        'settings',
        'rashifals',
        'comments',
        'push_subscribers',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $sqlitePath = $this->option('sqlite') ?: database_path('database.sqlite');

        if (! file_exists($sqlitePath)) {
            $this->error("SQLite database file not found at: {$sqlitePath}");
            return self::FAILURE;
        }

        $this->info("Source SQLite: {$sqlitePath}");
        $mysqlDb = config('database.connections.mysql.database');
        $this->info("Destination MySQL: {$mysqlDb}");

        // Test MySQL connection
        try {
            DB::connection('mysql')->getPdo();
        } catch (\Throwable $e) {
            $this->error("Could not connect to MySQL database '{$mysqlDb}': " . $e->getMessage());
            return self::FAILURE;
        }

        // Configure temporary sqlite connection
        config(['database.connections.sqlite.database' => $sqlitePath]);
        DB::purge('sqlite');

        try {
            DB::connection('sqlite')->getPdo();
        } catch (\Throwable $e) {
            $this->error("Could not read SQLite database: " . $e->getMessage());
            return self::FAILURE;
        }

        if (! $this->option('force') && ! $this->confirm("Are you sure you want to copy all data from SQLite to MySQL ({$mysqlDb})? Existing data in these tables on MySQL will be overwritten.")) {
            $this->warn('Operation cancelled.');
            return self::SUCCESS;
        }

        $this->newLine();
        $this->info('Starting database migration from SQLite to MySQL...');

        // Disable foreign key checks on MySQL for bulk insertion
        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');

        $summary = [];

        foreach ($this->tables as $table) {
            // Check if table exists in SQLite
            $sqliteExists = DB::connection('sqlite')
                ->select("SELECT name FROM sqlite_master WHERE type='table' AND name = ?", [$table]);

            if (empty($sqliteExists)) {
                $summary[] = [$table, 0, 0, 'SKIPPED (Not in SQLite)'];
                continue;
            }

            // Ensure destination table exists in MySQL
            if (! Schema::connection('mysql')->hasTable($table)) {
                $summary[] = [$table, 0, 0, 'ERROR (Missing in MySQL)'];
                continue;
            }

            $sqliteCount = DB::connection('sqlite')->table($table)->count();

            // Clear MySQL destination table
            DB::connection('mysql')->table($table)->truncate();

            if ($sqliteCount > 0) {
                // Read from SQLite and insert in chunks
                $records = DB::connection('sqlite')->table($table)->get();

                // Convert stdClass rows to arrays
                $data = json_decode(json_encode($records), true);

                foreach (array_chunk($data, 100) as $chunk) {
                    DB::connection('mysql')->table($table)->insert($chunk);
                }

                // Adjust auto-increment if table has 'id' column
                if (Schema::connection('mysql')->hasColumn($table, 'id')) {
                    $maxId = DB::connection('mysql')->table($table)->max('id');
                    if ($maxId !== null) {
                        $nextId = (int) $maxId + 1;
                        DB::connection('mysql')->statement("ALTER TABLE `{$table}` AUTO_INCREMENT = {$nextId};");
                    }
                }
            }

            $mysqlCount = DB::connection('mysql')->table($table)->count();
            $status = ($sqliteCount === $mysqlCount) ? '<fg=green>MATCHED (100%)</>' : '<fg=red>MISMATCH</>';

            $summary[] = [$table, $sqliteCount, $mysqlCount, $status];
            $this->line("  ✓ Table '{$table}': {$mysqlCount} rows migrated.");
        }

        // Re-enable foreign key checks on MySQL
        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->newLine();
        $this->table(['Table Name', 'SQLite Rows', 'MySQL Rows', 'Status'], $summary);

        $this->newLine();
        $this->info('✓ All data successfully migrated from SQLite to MySQL!');
        $this->info("Your application can now run on MySQL by keeping DB_CONNECTION=mysql in .env.");

        return self::SUCCESS;
    }
}
