<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    protected $fillable = [
        'operator_id',
        'name',
        'bus_number',
         'bus_type',
        'total_seats',
        'is_active',
    ];


     public function seats()
   {
       return $this->hasMany(Seat::class);
   }

   public function layout()
   {
       return $this->hasOne(BusLayout::class);
   }

   public function amenities()
   {
       return $this->belongsToMany(Amenity::class, 'bus_amenities');
   }

}
