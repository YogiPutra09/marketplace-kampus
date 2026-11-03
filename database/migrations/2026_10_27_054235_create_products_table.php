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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel users dan categories
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            
            // Detail barang
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->unsignedBigInteger('price');
            
            // Kondisi barang: baru/mulus, bagus, ada minus/pemakaian wajar
            $table->enum('condition', ['like_new', 'good', 'fair'])->default('good');
            
            // Status ketersediaan barang
            $table->enum('status', ['available', 'sold'])->default('available');
            
            // Path file foto barang di folder storage
            $table->string('image')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
