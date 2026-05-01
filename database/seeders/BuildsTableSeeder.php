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
            'name' => 'PC Gaming de bajo presupuesto',
            'is_public' => true,
            'user_id' => 1,
            'motherboard_id' => 10027,
            'cpu_id' => 28,
            'gpu_id' => 20046,
            'ram_id' => 60009,
            'psu_id' => 40096,
            'drive_id' => 30043,
            'chassis_id' => 50048,
        ]);
        $build = Build::create([
            'name' => 'PC Gaming de medio presupuesto',
            'is_public' => true,
            'user_id' => 1,
            'motherboard_id' => 100179,
            'cpu_id' => 16,
            'gpu_id' => 20002,
            'ram_id' => 60020,
            'psu_id' => 40010,
            'drive_id' => 30001,
            'chassis_id' => 50005,
        ]);
        $build = Build::create([
            'name' => 'PC Gaming de alto presupuesto',
            'is_public' => true,
            'user_id' => 1,
            'motherboard_id' => 100512,
            'cpu_id' => 25,
            'gpu_id' => 20044,
            'ram_id' => 60066,
            'psu_id' => 40149,
            'drive_id' => 30038,
            'chassis_id' => 50007,
        ]);
    }
}
