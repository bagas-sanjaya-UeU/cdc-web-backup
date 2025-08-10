<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Booking;
use App\Models\Table;

class Place extends Model
{
     use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relasi: Satu tempat memiliki banyak meja.
     */
    public function tables()
    {
        return $this->hasMany(Table::class);
    }

    /**
     * Relasi: Satu tempat bisa memiliki banyak booking.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
