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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('name');
            $table->index('created_at');
        });
    }

    /**
     * Migration'ları geri al
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
