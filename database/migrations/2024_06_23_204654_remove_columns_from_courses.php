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
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
            $table->string('type');
            $table->year('year');
            $table->tinyInteger('semester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('start_date');
            $table->string('end_date');
            $table->dropColumn(['year', 'semester', 'type']);
        });
    }
};
