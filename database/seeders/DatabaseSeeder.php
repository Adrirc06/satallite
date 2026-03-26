<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/sql/components.sql');

        if (File::exists($path)) {

            $sql = File::get($path);

            // Ejecutamos el SQL sin preparar para permitir múltiples sentencias
            DB::unprepared($sql);

            $this->command->info("Componetes cargados correctamente.");
        } else {
            $this->command->error("No se encontró el archivo {$path}.");
        }
    }
}
