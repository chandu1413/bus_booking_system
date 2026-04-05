<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    public function buses()
   {
       return $this->belongsToMany(Bus::class, 'bus_amenities');
   }

}
