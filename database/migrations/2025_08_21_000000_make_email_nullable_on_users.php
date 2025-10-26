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
        $driver = DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME);
        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE users ALTER COLUMN email TYPE VARCHAR(255)');
            DB::statement('ALTER TABLE users ALTER COLUMN email DROP NOT NULL');
        } else {
            // MySQL / MariaDB
            DB::statement('ALTER TABLE `users` MODIFY `email` VARCHAR(255) NULL');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $driver = DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME);
        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE users ALTER COLUMN email TYPE VARCHAR(255)');
            DB::statement('ALTER TABLE users ALTER COLUMN email SET NOT NULL');
        } else {
            DB::statement('ALTER TABLE `users` MODIFY `email` VARCHAR(255) NOT NULL');
        }
    }
};
