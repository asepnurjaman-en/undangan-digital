<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
			[
				'name'		=> 'Asep Nurjaman',
				'email'		=> 'media.asepnurjaman107@gmail.com',
				'password' 	=> bcrypt('admin123'),
				'role'		=> 'developer',
			],
            [
				'name'		=> 'Admin',
				'email'		=> 'admin@gmail.com',
				'password' 	=> bcrypt('admin123'),
				'role'		=> 'admin',
			]
        ]);
    }
}
