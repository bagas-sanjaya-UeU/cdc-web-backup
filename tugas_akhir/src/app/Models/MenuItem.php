<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Booking;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_available',
        'is_recommended',
        'price',
        'image',
        'category', // Tambahkan kolom kategori
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Relasi: Satu menu bisa ada di banyak booking.
     * Menggunakan tabel pivot 'booking_menu_item'.
     */
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_menu_item')
                    ->withPivot('quantity', 'price') // Mengambil data tambahan dari pivot
                    ->withTimestamps();
    }
}
