<?php

use App\Models\Admin;
use App\Models\Course;
use App\Models\Institution;
use App\Models\State;
use App\Models\User;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('login')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('cost');
            $table->string('start_date');
            $table->string('end_date');
            $table->foreignIdFor(Institution::class)->constrained()->restrictOnDelete();
            $table->timestamps();
        });
        Schema::create('course_users', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Course::class)->constrained()->restrictOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->boolean('is_paid')->default(false);
            $table->string('payment_id')->nullable();
            $table->timestamps();
        });
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(Admin::class)->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('name');
            $table->foreignIdFor(State::class)->constrained()->restrictOnDelete();
            $table->string('manager_name');
            $table->string('manager_tel');
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('region_name');
            $table->string('tel');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('can_edit')->default(true);
            $table->boolean('is_super')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
        Schema::dropIfExists('institutions');
        Schema::dropIfExists('states');
        Schema::dropIfExists('course_users');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
