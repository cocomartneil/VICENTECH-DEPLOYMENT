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
        Schema::table('announcements', function (Blueprint $table) {
            $table->date('date')->nullable()->after('description');
            $table->longText('image_data')->nullable()->after('date');
            $table->string('image_mime')->nullable()->after('image_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $cols = ['date', 'image_data', 'image_mime'];
        $toDrop = array_filter($cols, function ($c) {
            return Schema::hasColumn('announcements', $c);
        });
        if (!empty($toDrop)) {
            Schema::table('announcements', function (Blueprint $table) use ($toDrop) {
                $table->dropColumn($toDrop);
            });
        }
    }
};
