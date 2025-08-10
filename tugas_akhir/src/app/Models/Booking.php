<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Place;
use App\Models\Table;
use App\Models\MenuItem;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'place_id',
        'booking_date',
        'booking_time',
        'number_of_people',
        'notes',
        'subtotal',
        'tax',
        'total_amount',
        'down_payment_amount',
        'payment_proof',
        'payment_status', // Status pembayaran dari midtrans
        'status',
        'status_description', // Deskripsi status jika diperlukan
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'booking_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'down_payment_amount' => 'decimal:2',
    ];

    /**
     * Relasi: Satu booking dimiliki oleh satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Satu booking berlokasi di satu tempat.
     */
    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    /**
     * Relasi: Satu booking bisa memesan banyak meja.
     * Menggunakan tabel pivot 'booking_table'.
     */
    public function tables()
    {
        return $this->belongsToMany(Table::class, 'booking_table');
    }

    /**
     * Relasi: Satu booking bisa memesan banyak menu.
     * Menggunakan tabel pivot 'booking_menu_item'.
     */
    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'booking_menu_item')
                    ->withPivot('quantity', 'price') // Mengambil data quantity & price dari pivot
                    ->withTimestamps();
    }
}
