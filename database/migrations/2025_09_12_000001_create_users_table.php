<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migration'ları çalıştır
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->rememberToken();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('email');
            $table->index('role');
            $table->index('created_at');
        });
    }

    /**
     * Migration'ları geri al
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
