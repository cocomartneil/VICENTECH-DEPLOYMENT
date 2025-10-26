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
        Schema::table('news', function (Blueprint $table) {
            $table->string('image')->nullable();
            $table->string('title');
            // $table->string('date')->nullable(false);
            // $table->string('quote')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $cols = ['image', 'title', 'date', 'quote'];
        $toDrop = array_filter($cols, function ($c) {
            return Schema::hasColumn('news', $c);
        });
        if (!empty($toDrop)) {
            Schema::table('news', function (Blueprint $table) use ($toDrop) {
                $table->dropColumn($toDrop);
            });
        }
    }
};
