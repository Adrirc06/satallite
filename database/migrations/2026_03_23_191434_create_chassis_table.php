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
        Schema::create('chassis', function (Blueprint $table) {
            $table->id()->from(50001);
            $table->string('name', 255);
            $table->decimal('price', 10, 2)->nullable();
            $table->foreignId('chassis_type_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chassis');
    }
};
