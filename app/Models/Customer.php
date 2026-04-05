<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'loyalty_points',
    ];

    // Relationship: belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: has many bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}