<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->nullableMorphs('loggable');
            $table->string('action');
            $table->text('description');
            $table->json('properties')->nullable();
            $table->timestamp('created_at');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            // Drop the loggable index if it exists
            $table->dropIndex('activity_logs_loggable_type_loggable_id_index');
        });
        Schema::dropIfExists('activity_logs');
    }
};