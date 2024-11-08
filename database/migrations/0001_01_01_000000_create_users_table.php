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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->boolean('is_active')->default(0);
            $table->boolean('is_admin')->default(0);
            $table->text('card_photo')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->string('student_phone_number')->unique()->nullable();
            $table->string('parent_phone_number')->unique()->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('wallet')->default(0);
            $table->rememberToken();
            $table->foreignId('year_id')->nullable()->constrained('years', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('division_id')->nullable()->constrained('divisions', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('governorate_id')->nullable()->constrained('governorates', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
