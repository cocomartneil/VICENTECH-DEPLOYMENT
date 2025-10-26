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
        Schema::table('prayer_requests', function (Blueprint $table) {
            $table->boolean('is_read')->default(false)->after('status');
            $table->string('title')->nullable()->after('is_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $cols = ['is_read', 'title'];
        $toDrop = array_filter($cols, function ($c) {
            return Schema::hasColumn('prayer_requests', $c);
        });
        if (!empty($toDrop)) {
            Schema::table('prayer_requests', function (Blueprint $table) use ($toDrop) {
                $table->dropColumn($toDrop);
            });
        }
    }
};
