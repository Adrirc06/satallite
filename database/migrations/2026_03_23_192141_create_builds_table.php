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
        Schema::create('builds', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->boolean('is_public')->default(true);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('motherboard_id')->constrained();
            $table->foreignId('cpu_id')->nullable()->constrained();
            $table->foreignId('gpu_id')->nullable()->constrained();
            $table->foreignId('ram_id')->constrained();
            $table->foreignId('psu_id')->constrained();
            $table->foreignId('drive_id')->constrained();
            $table->foreignId('chassis_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('builds');
    }
};
