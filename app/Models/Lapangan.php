<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'harga_per_jam',
        'jam_buka',
        'jam_tutup',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

