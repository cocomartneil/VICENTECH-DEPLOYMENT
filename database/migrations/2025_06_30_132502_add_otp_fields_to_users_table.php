<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('otp')->nullable()->after('remember_token');
            $table->timestamp('otp_expires_at')->nullable()->after('otp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $cols = ['otp', 'otp_expires_at'];
        $toDrop = array_filter($cols, function ($c) {
            return Schema::hasColumn('users', $c);
        });
        if (!empty($toDrop)) {
            Schema::table('users', function (Blueprint $table) use ($toDrop) {
                $table->dropColumn($toDrop);
            });
        }
    }
};
