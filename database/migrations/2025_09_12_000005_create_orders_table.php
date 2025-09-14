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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('user_id');
            $table->index('status');
            $table->index('total_amount');
            $table->index('created_at');
        });
    }

    /**
     * Migration'ları geri al
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
