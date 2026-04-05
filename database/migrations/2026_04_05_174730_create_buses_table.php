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
        Schema::create('buses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('operator_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('bus_number')->unique();

            $table->enum('bus_type', [
                'ac',
                'non_ac',
                'sleeper',
                'semi_sleeper'
            ]);

            $table->integer('total_seats');

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};
