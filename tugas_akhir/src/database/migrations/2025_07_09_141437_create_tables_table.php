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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel places
            $table->foreignId('place_id')->constrained('places')->onDelete('cascade');
            $table->string('image')->nullable(); // Path/URL gambar menu
            $table->string('table_number'); // Nomor atau kode meja
            $table->integer('capacity'); // Kapasitas meja
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
