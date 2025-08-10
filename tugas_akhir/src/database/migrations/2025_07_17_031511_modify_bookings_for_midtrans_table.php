<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Hapus kolom upload bukti
            if (Schema::hasColumn('bookings', 'payment_proof')) {
                $table->dropColumn('payment_proof');
            }
            // Tambahkan kolom untuk status pembayaran Midtrans
            $table->string('payment_status')->default('pending')->after('status_description');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_proof')->nullable();
            $table->dropColumn('payment_status');
        });
    }
};