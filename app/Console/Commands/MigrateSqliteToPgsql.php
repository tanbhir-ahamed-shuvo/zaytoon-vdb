<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateSqliteToPgsql extends Command
{
    protected $signature = 'data:migrate-sqlite-to-pgsql
        {--source=sqlite : Source DB connection name}
        {--target=pgsql : Target DB connection name}
        {--table=* : Optional table list. If omitted, all app tables are copied}
        {--dry-run : Show what will be copied without writing data}
        {--force : Skip confirmation prompt}';

    protected $description = 'One-time copy from SQLite to PostgreSQL (truncate target tables, copy rows, reset id sequences).';

    public function handle(): int
    {
        $source = (string) $this->option('source');
        $target = (string) $this->option('target');
        $dryRun = (bool) $this->option('dry-run');

        if (!array_key_exists($source, config('database.connections', []))) {
            $this->error("Source connection [{$source}] is not configured.");
            return self::FAILURE;
        }

        if (!array_key_exists($target, config('database.connections', []))) {
            $this->error("Target connection [{$target}] is not configured.");
            return self::FAILURE;
        }

        if ($source === $target) {
            $this->error('Source and target connections must be different.');
            return self::FAILURE;
        }

        $sourceDriver = config("database.connections.{$source}.driver");
        $targetDriver = config("database.connections.{$target}.driver");

        if ($sourceDriver !== 'sqlite') {
            $this->error("Source [{$source}] must use sqlite driver. Current: {$sourceDriver}");
            return self::FAILURE;
        }

        if ($targetDriver !== 'pgsql') {
            $this->error("Target [{$target}] must use pgsql driver. Current: {$targetDriver}");
            return self::FAILURE;
        }

        try {
            DB::connection($source)->getPdo();
            DB::connection($target)->getPdo();
        } catch (\Throwable $e) {
            $this->error('Database connection failed: '.$e->getMessage());
            return self::FAILURE;
        }

        $selectedTables = (array) $this->option('table');
        $tables = $this->resolveTables($source, $selectedTables);

        if ($tables === []) {
            $this->warn('No tables found to migrate.');
            return self::SUCCESS;
        }

        $this->newLine();
        $this->info('Migration plan');
        $this->line("  Source: {$source} ({$sourceDriver})");
        $this->line("  Target: {$target} ({$targetDriver})");
        $this->line('  Tables: '.implode(', ', $tables));
        $this->newLine();

        if (!$dryRun && !(bool) $this->option('force')) {
            if (!$this->confirm('This will TRUNCATE target tables and copy data. Continue?', false)) {
                $this->warn('Migration cancelled.');
                return self::SUCCESS;
            }
        }

        $copiedRows = 0;

        foreach ($tables as $table) {
            if (!Schema::connection($target)->hasTable($table)) {
                $this->warn("Skipping [{$table}] because it does not exist on target.");
                continue;
            }

            $sourceColumns = Schema::connection($source)->getColumnListing($table);
            $targetColumns = Schema::connection($target)->getColumnListing($table);
            $columns = array_values(array_intersect($sourceColumns, $targetColumns));

            if ($columns === []) {
                $this->warn("Skipping [{$table}] because there are no common columns.");
                continue;
            }

            $rowCount = DB::connection($source)->table($table)->count();
            $this->line("- {$table}: {$rowCount} row(s)");

            if ($dryRun) {
                continue;
            }

            DB::connection($target)->statement('TRUNCATE TABLE "'.$table.'" RESTART IDENTITY CASCADE');

            DB::connection($source)
                ->table($table)
                ->orderBy($columns[0])
                ->chunk(500, function ($rows) use ($target, $table, $columns, &$copiedRows): void {
                    $payload = [];
                    foreach ($rows as $row) {
                        $record = [];
                        foreach ($columns as $column) {
                            $record[$column] = $row->{$column};
                        }
                        $payload[] = $record;
                    }

                    if ($payload !== []) {
                        DB::connection($target)->table($table)->insert($payload);
                        $copiedRows += count($payload);
                    }
                });

            $this->resetSequenceIfNeeded($target, $table, $columns);
        }

        if ($dryRun) {
            $this->info('Dry-run complete. No data was written.');
            return self::SUCCESS;
        }

        $this->newLine();
        $this->info("Done. Copied {$copiedRows} row(s) to PostgreSQL.");
        return self::SUCCESS;
    }

    /**
     * @return array<int, string>
     */
    private function resolveTables(string $sourceConnection, array $selectedTables): array
    {
        if ($selectedTables !== []) {
            return array_values(array_unique(array_map('strval', $selectedTables)));
        }

        $rows = DB::connection($sourceConnection)
            ->select("SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%'");

        $all = array_map(static fn ($row): string => (string) $row->name, $rows);

        $excluded = [
            'migrations',
            'cache',
            'cache_locks',
            'jobs',
            'job_batches',
            'failed_jobs',
            'sessions',
            'password_reset_tokens',
        ];

        $appPriority = [
            'users',
            'field_officers',
            'geo_data',
            'submissions',
            'vdb_entries',
            'account_informations',
        ];

        $filtered = array_values(array_filter($all, static fn (string $table): bool => !in_array($table, $excluded, true)));

        $ordered = [];
        foreach ($appPriority as $table) {
            if (in_array($table, $filtered, true)) {
                $ordered[] = $table;
            }
        }

        foreach ($filtered as $table) {
            if (!in_array($table, $ordered, true)) {
                $ordered[] = $table;
            }
        }

        return $ordered;
    }

    /**
     * @param array<int, string> $columns
     */
    private function resetSequenceIfNeeded(string $targetConnection, string $table, array $columns): void
    {
        if (!in_array('id', $columns, true)) {
            return;
        }

        DB::connection($targetConnection)->statement(
            "SELECT setval(pg_get_serial_sequence('{$table}', 'id'), COALESCE((SELECT MAX(id) FROM \"{$table}\"), 1), true)"
        );
    }
}
