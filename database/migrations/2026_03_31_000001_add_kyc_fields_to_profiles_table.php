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
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('pan_no')->nullable();
            $table->string('pan_card_file')->nullable();
            $table->string('aadhar_no')->nullable();
            $table->string('aadhar_card_file')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'pan_no',
                'pan_card_file',
                'aadhar_no',
                'aadhar_card_file',
            ]);
        });
    }
};
