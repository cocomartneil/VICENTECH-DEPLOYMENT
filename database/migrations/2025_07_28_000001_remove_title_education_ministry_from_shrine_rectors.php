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
        $cols = ['title', 'education', 'ministry_experience'];
        $toDrop = array_filter($cols, function ($c) {
            return Schema::hasColumn('shrine_rectors', $c);
        });
        if (!empty($toDrop)) {
            try {
                Schema::table('shrine_rectors', function (Blueprint $table) use ($toDrop) {
                    $table->dropColumn($toDrop);
                });
            } catch (\Exception $e) {
                // If DB reports missing column or any other issue, ignore to allow fresh migrations to continue
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shrine_rectors', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->string('education')->nullable();
            $table->string('ministry_experience')->nullable();
        });
    }
};
