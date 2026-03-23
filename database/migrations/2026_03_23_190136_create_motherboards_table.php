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
        Schema::create('motherboards', function (Blueprint $table) {
            $table->id()->from(10001);
            $table->string('name', 255);
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('max_memory')->nullable();
            $table->foreignId('socket_id')->constrained();
            $table->foreignId('form_factor_id')->constrained();
            $table->foreignId('ram_type_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motherboards');
    }
};
