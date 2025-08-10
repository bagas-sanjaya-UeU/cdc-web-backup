<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Place;
use App\Models\Booking;

class Table extends Model
{
   
    use HasFactory;

    protected $fillable = [
        'place_id',
        'image', // Path/URL gambar meja
        'table_number',
        'capacity',
    ];

    /**
     * Relasi: Satu meja dimiliki oleh satu tempat.
     */
    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    /**
     * Relasi: Satu meja bisa dibooking berkali-kali (di banyak booking).
     * Menggunakan tabel pivot 'booking_table'.
     */
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_table');
    }
}
