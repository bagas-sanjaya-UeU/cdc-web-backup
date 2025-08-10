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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique(); // Kode unik booking, e.g., BK-20240710-001
            
            // Relasi ke pelanggan dan tempat
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('place_id')->constrained('places');

            $table->date('booking_date'); // Tanggal booking
            $table->time('booking_time'); // Jam booking
            $table->integer('number_of_people'); // Jumlah orang
            $table->text('notes')->nullable(); // Catatan dari pelanggan

            // Detail finansial
            $table->bigInteger('subtotal'); // Subtotal dalam satuan terkecil (misal: sen)
            $table->bigInteger('tax')->default(0); // Pajak dalam satuan terkecil (misal: sen)
            $table->bigInteger('total_amount'); // Total dalam satuan terkecil (misal: sen)
            $table->bigInteger('down_payment_amount')->default(0); // Uang muka (DP) dalam satuan terkecil (misal: sen)

            // Detail pembayaran
            $table->string('payment_proof')->nullable(); // Path/URL bukti transfer

            // Status booking
            // 'pending_payment': Menunggu pembayaran DP
            // 'pending_confirmation': Menunggu konfirmasi admin
            // 'confirmed': Booking dikonfirmasi
            // 'completed': Selesai
            // 'cancelled': Dibatalkan
            $table->string('status')->default('pending_payment');
            $table->string('status_description')->nullable(); // Deskripsi status jika diperlukan
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
