<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use App\Models\User;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $totalRecords = 999;
        $batchSize = 250;
        $users = [];
        $password =Hash::make('password123');
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'], // Buscar por email
            [
                'user_id' => Str::uuid(),
                'name' => 'Admin User',
                'password' => Hash::make('adminpassword'),
                'birth_date' => '1990-01-01',
                'active' => true,
                'created_at' => now(),
                'created_by' => 'system',
            ]
        );

        for ($i = 0; $i < $totalRecords; $i++) {
            $users[] = [
                'user_id' => Str::uuid(),
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => $password,
                'birth_date' => $faker->date('Y-m-d', '2005-12-31'),
                'active' => $faker->boolean(90),
                'created_at' => now(),
                'created_by' =>$admin->user_id,
            ];
        }
  
        foreach (array_chunk($users, $batchSize) as $chunk) {
            User::insert($chunk);
        }
    }
}
