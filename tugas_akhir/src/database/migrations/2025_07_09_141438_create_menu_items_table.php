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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama menu
            $table->text('description')->nullable(); // Deskripsi menu
            $table->string('category')->nullable(); // Kategori menu, bisa diisi dengan nama kategori atau ID kategori
            $table->string('is_available')->default('yes'); // Status ketersediaan: 'yes' atau 'no'
            $table->string('is_recommended')->default('no'); // Status rekomendasi: 'yes' atau 'no'
            // Gunakan decimal untuk harga agar presisi
            $table->bigInteger('price'); // Harga menu dalam satuan terkecil (misal: sen)
            $table->string('image')->nullable(); // Path/URL gambar menu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
