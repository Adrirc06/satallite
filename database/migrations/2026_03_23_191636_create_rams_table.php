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
        Schema::create('rams', function (Blueprint $table) {
            $table->id()->from(60001);
            $table->string('name', 255);
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('cas_latency')->nullable();
            $table->integer('size')->nullable();
            $table->integer('modules')->nullable();
            $table->integer('speed')->nullable();
            $table->foreignId('ram_type_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rams');
    }
};
