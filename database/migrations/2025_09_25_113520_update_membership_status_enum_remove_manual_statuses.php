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
            // For PostgreSQL:
            // First check if the type exists
            $typeExists = DB::select("SELECT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'membership_status_enum')");
            $typeExists = $typeExists[0]->exists;

            if ($typeExists) {
                // First, drop the default constraint
                DB::statement("ALTER TABLE users ALTER COLUMN membership_status DROP DEFAULT");
                
                // Then create new type and convert
                DB::statement("CREATE TYPE membership_status_enum_new AS ENUM ('active', 'inactive', 'visitor', 'new_member')");
                DB::statement("ALTER TABLE users ALTER COLUMN membership_status TYPE membership_status_enum_new USING membership_status::text::membership_status_enum_new");
                DB::statement("DROP TYPE membership_status_enum");
                DB::statement("ALTER TYPE membership_status_enum_new RENAME TO membership_status_enum");
                
                // Finally, set the new default
                DB::statement("ALTER TABLE users ALTER COLUMN membership_status SET DEFAULT 'new_member'");
            } else {
                // If type doesn't exist, create it directly
                DB::statement("CREATE TYPE membership_status_enum AS ENUM ('active', 'inactive', 'visitor', 'new_member')");
                DB::statement("ALTER TABLE users ALTER COLUMN membership_status DROP DEFAULT");
                DB::statement("ALTER TABLE users ALTER COLUMN membership_status TYPE membership_status_enum USING membership_status::text::membership_status_enum");
                DB::statement("ALTER TABLE users ALTER COLUMN membership_status SET DEFAULT 'new_member'");
            }
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
            // For PostgreSQL:
            // Check if the type exists
            $typeExists = DB::select("SELECT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'membership_status_enum')");
            $typeExists = $typeExists[0]->exists;

            if ($typeExists) {
                // First, drop the default constraint
                DB::statement("ALTER TABLE users ALTER COLUMN membership_status DROP DEFAULT");
                
                // Then create new type and convert
                DB::statement("CREATE TYPE membership_status_enum_old AS ENUM ('active', 'inactive', 'visitor', 'new_member', 'transferred_out', 'deceased', 'suspended')");
                DB::statement("ALTER TABLE users ALTER COLUMN membership_status TYPE membership_status_enum_old USING membership_status::text::membership_status_enum_old");
                DB::statement("DROP TYPE membership_status_enum");
                DB::statement("ALTER TYPE membership_status_enum_old RENAME TO membership_status_enum");
                
                // Finally, set the new default
                DB::statement("ALTER TABLE users ALTER COLUMN membership_status SET DEFAULT 'new_member'");
            } else {
                // If type doesn't exist, create it directly
                DB::statement("CREATE TYPE membership_status_enum AS ENUM ('active', 'inactive', 'visitor', 'new_member', 'transferred_out', 'deceased', 'suspended')");
                DB::statement("ALTER TABLE users ALTER COLUMN membership_status DROP DEFAULT");
                DB::statement("ALTER TABLE users ALTER COLUMN membership_status TYPE membership_status_enum USING membership_status::text::membership_status_enum");
                DB::statement("ALTER TABLE users ALTER COLUMN membership_status SET DEFAULT 'new_member'");
            }
        } else {
            // MySQL syntax
            DB::statement("ALTER TABLE users MODIFY COLUMN membership_status ENUM('active', 'inactive', 'visitor', 'new_member', 'transferred_out', 'deceased', 'suspended') DEFAULT 'new_member'");
        }
    }
};
