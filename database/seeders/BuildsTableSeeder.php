<?php

namespace Database\Seeders;

use App\Models\Build;
use Illuminate\Database\Seeder;

class BuildsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $build = Build::create([
            'name' => 'prueba_build',
            'is_public' => true,
            'user_id' => 1,
            'motherboard_id' => 10001,
            'cpu_id' => 1,
            'gpu_id' => 20001,
            'ram_id' => 60001,
            'psu_id' => 40001,
            'drive_id' => 30001,
            'chassis_id' => 50001,
        ]);
    }
}
