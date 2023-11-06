<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::insert([
			[
				'type'		=> 'title',
				'title'		=> 'Judul Tab',
				'content'	=> 'Web Template',
				'user_id'	=> 1
			],
			[
				'type'		=> 'icon',
				'title'		=> 'Ikon Tab',
				'content'	=> 'icon.png',
				'user_id'	=> 1
			],
			[
				'type'		=> 'logo',
				'title'		=> 'Logo',
				'content'	=> 'logo.png',
				'user_id'	=> 1
			],
			[
				'type'		=> 'color',
				'title'		=> 'Warna Latar Tab',
				'content'	=> '#fb6f92',
				'user_id'	=> 1
			],
			[
				'type'		=> 'meta description',
				'title'		=> 'Meta Description',
				'content'	=> 'Web Template adalah bla bla bla',
				'user_id'	=> 1
			],
			[
				'type'		=> 'meta keywords',
				'title'		=> 'Meta Keywords',
				'content'	=> 'Web Template, keyword, point',
				'user_id'	=> 1
			],
			[
				'type'		=> 'maintenance',
				'title'		=> 'Pemeliharaan',
				'content'	=> 'off',
				'user_id'	=> 1
			],
		]);
    }
}
