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
        Schema::create('cpus', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('cores')->nullable();
            $table->integer('tdp')->nullable();
            $table->decimal('frequency', 5, 2)->nullable();
            $table->decimal('max_frequency', 5, 2)->nullable();
            $table->foreignId('socket_id')->constrained();
            $table->foreignId('igpu_id')->constrained()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpus');
    }
};
