<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Package::insert([
			[
				'title' 	=> 'Uji Coba',
				'slug'		=> 'try',
				'content'	=> '{"gift":true,"e-invitation":false,"filter-ig":false,"story":true,"live-stream":false,"private-invitation":false,"event":true,"free-text":false,"event-count":"5","story-count":"5","gallery-photo":"4","smart-wa":false,"manual-wa":true,"guest":null,"gallery-video":"1","music":"template","template":["basic"],"active":"3"}',
				'price'	    => '0',
				'publish'	=> 'publish'
			]
		]);
    }
}
