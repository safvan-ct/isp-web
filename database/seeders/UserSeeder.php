<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'Developer',
                'email'      => 'dev@email.com',
                'password'   => bcrypt('password'),
                'role'       => 'Developer',
            ],
            [
                'first_name' => 'Owner',
                'email'      => 'owner@email.com',
                'password'   => bcrypt('password'),
                'role'       => 'Owner',
            ],
            [
                'first_name' => 'Admin',
                'email'      => 'admin@email.com',
                'password'   => bcrypt('password'),
                'role'       => 'Admin',
            ],
            [
                'first_name' => 'Customer',
                'email'      => 'safvan@email.com',
                'password'   => bcrypt('password'),
                'role'       => 'Customer',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'role'       => $userData['role'],
                    'first_name' => $userData['first_name'],
                    'password'   => $userData['password'],
                ]
            );

            $user->assignRole($userData['role']);
        }
    }
}
