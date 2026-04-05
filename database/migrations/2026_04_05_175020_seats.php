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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bus_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('seat_number');

            $table->enum('seat_type', ['seater', 'sleeper']);

            $table->enum('position', ['window', 'aisle', 'middle'])->nullable();

            $table->integer('row_number')->nullable();
            $table->integer('column_number')->nullable();

            $table->enum('deck', ['lower', 'upper'])->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['bus_id', 'seat_number']);
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
