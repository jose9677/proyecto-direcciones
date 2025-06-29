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
            $table->char('v_e', 1);
            $table->integer('ced', 32);
            $table->string('p_a', 255);
            $table->string('s_a', 255)->nullable();
            $table->string('p_n', 255);
            $table->string('s_n', 255)->nullable();
            $table->date('f_n');
            $table->string('login', 255)->unique();
            $table->string('email', 255)->unique();
            $table->boolean('email_active');
            $table->unsignedBigInteger('id_rol');
            $table->string('otp', 6)->nullable();
            $table->boolean('active');
            $table->boolean('employee');
            $table->string('api_token', 255)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->rememberToken();
            $table->timestamps();

            $table->primary(['v_e', 'ced']);

            $table->foreign('id_rol')->references('id_rol')->on('roles')->onUpdate('cascade');
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
