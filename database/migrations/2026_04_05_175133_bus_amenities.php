<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
   {
       Schema::create('bus_amenities', function (Blueprint $table) {
           $table->id();

           $table->foreignId('bus_id')
                 ->constrained()
                 ->cascadeOnDelete();

           $table->foreignId('amenity_id')
                 ->constrained()
                 ->cascadeOnDelete();

           $table->unique(['bus_id', 'amenity_id']);
       });
   }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
