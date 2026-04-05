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
       Schema::create('bus_layouts', function (Blueprint $table) {
           $table->id();

           $table->foreignId('bus_id')
                 ->constrained()
                 ->cascadeOnDelete();

           $table->integer('total_rows');
           $table->integer('total_columns');

           // aisle position (e.g., after column 2)
           $table->integer('aisle_after_column')->nullable();

           // decks (1 = normal, 2 = sleeper)
           $table->integer('deck_count')->default(1);

           $table->timestamps();
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
