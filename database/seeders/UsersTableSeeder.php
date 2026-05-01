<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'SATAllite',
            'email' => 'admin@satallite.com',
            'password' => bcrypt('admin'),
            'is_admin' => true,
        ]);

        $user = User::create([
            'name' => 'Usuario',
            'email' => 'user@satallite.com',
            'password' => bcrypt('user'),
            'is_admin' => false,
        ]);

        $user->createToken('admin')->plainTextToken;
        $user->createToken('user')->plainTextToken;
    }
}
