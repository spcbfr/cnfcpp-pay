<?php

use App\Models\Institution;
use App\Models\Major;
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
        Schema::create('majors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->tinyInteger('number_of_semesters');
            $table->timestamps();
        });
        Schema::create('institution_major', function (Blueprint $table) {
            $table->foreignIdFor(Major::class)->constrained()->restrictOnDelete();
            $table->foreignIdFor(Institution::class)->constrained()->restrictOnDelete();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('majors');
        Schema::dropIfExists('institution_major');
    }
};
