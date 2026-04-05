<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusLayout extends Model
{
       protected $fillable = [
       'bus_id',
       'total_rows',
       'total_columns',
       'aisle_after_column',
       'deck_count'
   ];

   public function bus()
   {
       return $this->belongsTo(Bus::class);
   }

}
