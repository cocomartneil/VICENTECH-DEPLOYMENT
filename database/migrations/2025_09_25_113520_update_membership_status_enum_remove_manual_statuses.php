<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update any users with manual statuses to 'inactive'
        DB::statement("UPDATE users SET membership_status = 'inactive' WHERE membership_status IN ('deceased', 'suspended', 'transferred_out')");
        
        if (DB::connection()->getDriverName() === 'pgsql') {
            // For PostgreSQL, we need to:
            // 1. Create new enum type
            // 2. Update column to use new type
            // 3. Drop old type
            DB::statement("CREATE TYPE membership_status_enum_new AS ENUM ('active', 'inactive', 'visitor', 'new_member')");
            DB::statement("ALTER TABLE users ALTER COLUMN membership_status TYPE membership_status_enum_new USING membership_status::text::membership_status_enum_new");
            DB::statement("ALTER TABLE users ALTER COLUMN membership_status SET DEFAULT 'new_member'");
            DB::statement("DROP TYPE IF EXISTS membership_status_enum");
            DB::statement("ALTER TYPE membership_status_enum_new RENAME TO membership_status_enum");
        } else {
            // MySQL syntax
            DB::statement("ALTER TABLE users MODIFY COLUMN membership_status ENUM('active', 'inactive', 'visitor', 'new_member') DEFAULT 'new_member'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            // For PostgreSQL, recreate the original enum type
            DB::statement("CREATE TYPE membership_status_enum_old AS ENUM ('active', 'inactive', 'visitor', 'new_member', 'transferred_out', 'deceased', 'suspended')");
            DB::statement("ALTER TABLE users ALTER COLUMN membership_status TYPE membership_status_enum_old USING membership_status::text::membership_status_enum_old");
            DB::statement("ALTER TABLE users ALTER COLUMN membership_status SET DEFAULT 'new_member'");
            DB::statement("DROP TYPE IF EXISTS membership_status_enum");
            DB::statement("ALTER TYPE membership_status_enum_old RENAME TO membership_status_enum");
        } else {
            // MySQL syntax
            DB::statement("ALTER TABLE users MODIFY COLUMN membership_status ENUM('active', 'inactive', 'visitor', 'new_member', 'transferred_out', 'deceased', 'suspended') DEFAULT 'new_member'");
        }
    }
};
