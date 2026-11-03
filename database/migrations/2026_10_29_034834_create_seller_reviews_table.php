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
        Schema::create('seller_reviews', function (Blueprint $table) {
            $table->id();
            // User yang memberikan ulasan (pembeli)
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
            // User penjual yang dinilai
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            // Nilai rating (1 sampai 5)
            $table->unsignedTinyInteger('rating');
            // Komentar / ulasan teks
            $table->text('comment');
            $table->timestamps();

            // Mencegah reviewer memberi ulasan berkali-kali ke penjual yang sama
            $table->unique(['reviewer_id', 'seller_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_reviews');
    }
};
