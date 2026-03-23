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
        Schema::create('psus', function (Blueprint $table) {
            $table->id()->from(40001);
            $table->string('name', 255);
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('power')->nullable();
            $table->foreignId('psu_type_id')->constrained();
            $table->foreignId('modularity_id')->constrained()->nullable();
            $table->foreignId('efficiency_id')->constrained()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('psus');
    }
};
