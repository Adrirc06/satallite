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
            'name' => 'admin',
            'email' => 'admin@satallite.com',
            'password' => bcrypt('passwordadmin'),
            'is_admin' => true,
        ]);

        $user = User::create([
            'name' => 'user',
            'email' => 'user@satallite.com',
            'password' => bcrypt('passworduser'),
            'is_admin' => false,
        ]);

        $user->createToken('admin')->plainTextToken;
        $user->createToken('user')->plainTextToken;
    }
}
