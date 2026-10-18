<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DatabaseBackupService
{
    /**
     * Generate and stream a downloadable .sql database backup file.
     */
    public function downloadBackup(string $customFilename = null): StreamedResponse
    {
        $filename = $customFilename ?: 'kapilvastudarpan_database_' . now()->format('Y_m_d_His') . '.sql';

        $headers = [
            'Content-Type' => 'application/sql; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $this->streamSqlDump();
        }, 200, $headers);
    }

    /**
     * Stream the SQL dump directly to output buffer.
     */
    public function streamSqlDump(): void
    {
        $connection = DB::connection();
        $driver = $connection->getDriverName();
        $pdo = $connection->getPdo();

        $out = fopen('php://output', 'w');

        fwrite($out, "-- =========================================================\n");
        fwrite($out, "-- Kapilvastu Darpan (कपिलवस्तु दर्पण) - Database SQL Dump\n");
        fwrite($out, "-- Application: " . config('app.name') . "\n");
        fwrite($out, "-- Environment: " . config('app.env') . "\n");
        fwrite($out, "-- Exported At: " . now()->toIso8601String() . " (" . (function_exists('nepali_date') ? nepali_date() : '') . ")\n");
        fwrite($out, "-- Database Driver: " . $driver . "\n");
        fwrite($out, "-- PHP Version: " . PHP_VERSION . "\n");
        fwrite($out, "-- Laravel Version: " . app()->version() . "\n");
        fwrite($out, "-- =========================================================\n\n");

        if ($driver === 'sqlite') {
            $this->dumpSqlite($pdo, $out);
        } else {
            $this->dumpMysql($connection, $pdo, $out);
        }

        fclose($out);
    }

    /**
     * Dump SQLite database to standard SQL stream.
     */
    protected function dumpSqlite(\PDO $pdo, $out): void
    {
        fwrite($out, "PRAGMA foreign_keys = OFF;\n");
        fwrite($out, "BEGIN TRANSACTION;\n\n");

        // Retrieve all user tables (exclude sqlite system tables)
        $tables = DB::select("SELECT name, sql FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%' ORDER BY name");

        foreach ($tables as $tableItem) {
            $table = $tableItem->name;
            $tableDdl = $tableItem->sql;

            if (empty($tableDdl)) {
                continue;
            }

            fwrite($out, "-- ---------------------------------------------------------\n");
            fwrite($out, "-- Table structure for `{$table}`\n");
            fwrite($out, "-- ---------------------------------------------------------\n");
            fwrite($out, "DROP TABLE IF EXISTS `{$table}`;\n");
            fwrite($out, $tableDdl . ";\n\n");

            // Dump data
            $rows = DB::table($table)->get();
            if ($rows->isNotEmpty()) {
                fwrite($out, "-- Dumping data for table `{$table}` (" . $rows->count() . " records)\n");

                foreach ($rows->chunk(100) as $chunk) {
                    foreach ($chunk as $row) {
                        $rowArray = (array) $row;
                        $columns = array_keys($rowArray);
                        $escapedColumns = array_map(fn ($c) => '`' . str_replace('`', '``', $c) . '`', $columns);
                        $escapedValues = array_map(fn ($val) => $this->quoteValue($val, $pdo), array_values($rowArray));

                        fwrite($out, "INSERT INTO `{$table}` (" . implode(', ', $escapedColumns) . ") VALUES (" . implode(', ', $escapedValues) . ");\n");
                    }
                }
                fwrite($out, "\n");
            }
        }

        // Dump indexes
        $indexes = DB::select("SELECT name, sql FROM sqlite_master WHERE type = 'index' AND sql IS NOT NULL AND name NOT LIKE 'sqlite_%' ORDER BY name");
        if (! empty($indexes)) {
            fwrite($out, "-- ---------------------------------------------------------\n");
            fwrite($out, "-- Indexes for database\n");
            fwrite($out, "-- ---------------------------------------------------------\n");
            foreach ($indexes as $index) {
                fwrite($out, $index->sql . ";\n");
            }
            fwrite($out, "\n");
        }

        fwrite($out, "COMMIT;\n");
        fwrite($out, "PRAGMA foreign_keys = ON;\n");
    }

    /**
     * Dump MySQL database to standard SQL stream.
     */
    protected function dumpMysql($connection, \PDO $pdo, $out): void
    {
        fwrite($out, "SET FOREIGN_KEY_CHECKS = 0;\n");
        fwrite($out, "SET NAMES utf8mb4;\n");
        fwrite($out, "SET time_zone = '+00:00';\n");
        fwrite($out, "SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';\n\n");

        $tablesResult = DB::select('SHOW TABLES');
        $dbName = $connection->getDatabaseName();
        $propertyKey = 'Tables_in_' . $dbName;

        foreach ($tablesResult as $row) {
            $table = $row->$propertyKey ?? array_values((array) $row)[0];

            fwrite($out, "-- ---------------------------------------------------------\n");
            fwrite($out, "-- Table structure for `{$table}`\n");
            fwrite($out, "-- ---------------------------------------------------------\n");
            fwrite($out, "DROP TABLE IF EXISTS `{$table}`;\n");

            $createTable = DB::select("SHOW CREATE TABLE `{$table}`");
            $ddl = $createTable[0]->{'Create Table'} ?? '';
            fwrite($out, $ddl . ";\n\n");

            $rows = DB::table($table)->get();
            if ($rows->isNotEmpty()) {
                fwrite($out, "-- Dumping data for table `{$table}` (" . $rows->count() . " records)\n");

                foreach ($rows->chunk(100) as $chunk) {
                    foreach ($chunk as $r) {
                        $rowArray = (array) $r;
                        $columns = array_keys($rowArray);
                        $escapedColumns = array_map(fn ($c) => '`' . str_replace('`', '``', $c) . '`', $columns);
                        $escapedValues = array_map(fn ($val) => $this->quoteValue($val, $pdo), array_values($rowArray));

                        fwrite($out, "INSERT INTO `{$table}` (" . implode(', ', $escapedColumns) . ") VALUES (" . implode(', ', $escapedValues) . ");\n");
                    }
                }
                fwrite($out, "\n");
            }
        }

        fwrite($out, "SET FOREIGN_KEY_CHECKS = 1;\n");
    }

    /**
     * Escape/quote values cleanly for SQL INSERT statements.
     */
    protected function quoteValue(mixed $value, \PDO $pdo): string
    {
        if (is_null($value)) {
            return 'NULL';
        }
        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }
        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        return $pdo->quote((string) $value);
    }

    /**
     * Get diagnostic stats about current database.
     */
    public function getDatabaseStats(): array
    {
        $connection = DB::connection();
        $driver = $connection->getDriverName();
        $databasePath = $driver === 'sqlite' ? config('database.connections.sqlite.database') : $connection->getDatabaseName();

        $sizeBytes = 0;
        if ($driver === 'sqlite' && File::exists($databasePath)) {
            $sizeBytes = File::size($databasePath);
        }

        $tablesCount = 0;
        if ($driver === 'sqlite') {
            $tablesCount = count(DB::select("SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%'"));
        } else {
            $tablesCount = count(DB::select('SHOW TABLES'));
        }

        return [
            'driver' => strtoupper($driver),
            'database' => basename($databasePath),
            'size_bytes' => $sizeBytes,
            'formatted_size' => $this->formatBytes($sizeBytes),
            'tables_count' => $tablesCount,
            'articles_count' => DB::table('articles')->count(),
            'users_count' => DB::table('users')->count(),
            'categories_count' => DB::table('categories')->count(),
            'settings_count' => DB::table('settings')->count(),
            'default_filename' => 'database.sql',
            'timestamped_filename' => 'kapilvastudarpan_database_' . now()->format('Y_m_d_His') . '.sql',
        ];
    }

    /**
     * Format bytes to readable size string (e.g. 897 KB).
     */
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $base = log($bytes, 1024);
        $pow = floor($base);

        return round(pow(1024, $base - $pow), $precision) . ' ' . ($units[$pow] ?? 'B');
    }
}
