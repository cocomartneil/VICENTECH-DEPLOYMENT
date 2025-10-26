<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use PDO;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Make this migration DB-driver aware: MySQL uses MODIFY with backticks,
        // Postgres uses ALTER COLUMN ... TYPE / DROP NOT NULL.
        // Use multiple detection methods (PDO driver name, config, env) to be
        // resilient in different runtime environments.
        $pdoDriver = null;
        try {
            $pdoDriver = DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME);
        } catch (\Throwable $e) {
            // ignore — we'll fallback to config/env below
        }

        $driver = strtolower((string) ($pdoDriver ?? config('database.default') ?? env('DB_CONNECTION')));

        // Consider both 'pgsql' and any value containing 'post' as Postgres
        $isPg = strpos($driver, 'pgsql') !== false || strpos($driver, 'post') !== false;

        try {
            if ($isPg) {
                // Use Postgres-compatible statements
                DB::statement('ALTER TABLE users ALTER COLUMN email TYPE VARCHAR(255)');
                DB::statement('ALTER TABLE users ALTER COLUMN email DROP NOT NULL');
            } else {
                // MySQL / MariaDB
                DB::statement('ALTER TABLE `users` MODIFY `email` VARCHAR(255) NULL');
            }
        } catch (\Throwable $e) {
            // Log to stderr so the deploy log contains the information
            error_log('[migration] make_email_nullable up - driver: ' . $driver . ' - error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $pdoDriver = null;
        try {
            $pdoDriver = DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME);
        } catch (\Throwable $e) {
            // ignore
        }

        $driver = strtolower((string) ($pdoDriver ?? config('database.default') ?? env('DB_CONNECTION')));
        $isPg = strpos($driver, 'pgsql') !== false || strpos($driver, 'post') !== false;

        try {
            if ($isPg) {
                DB::statement('ALTER TABLE users ALTER COLUMN email TYPE VARCHAR(255)');
                DB::statement('ALTER TABLE users ALTER COLUMN email SET NOT NULL');
            } else {
                DB::statement('ALTER TABLE `users` MODIFY `email` VARCHAR(255) NOT NULL');
            }
        } catch (\Throwable $e) {
            error_log('[migration] make_email_nullable down - driver: ' . $driver . ' - error: ' . $e->getMessage());
            throw $e;
        }
    }
};
