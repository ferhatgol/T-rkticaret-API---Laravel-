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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock_quantity')->default(0);
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('name');
            $table->index('price');
            $table->index('stock_quantity');
            $table->index('category_id');
            $table->index('created_at');
            
            // Full text search index for product search
            $table->fullText(['name', 'description']);
        });
    }

    /**
     * Migration'ları geri al
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
