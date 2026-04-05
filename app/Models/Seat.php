<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 

class Seat extends Model
{
    
    public function bus()
   {
       return $this->belongsTo(Bus::class);
   }

}
