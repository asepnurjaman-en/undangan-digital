<?php

namespace Database\Seeders;

use App\Models\Info;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Info::insert([
			[
				'type'		=> 'package',
				'title' 	=> 'Menu paket',
				'content'	=> '{"gift":"Amplop Digital","e-invitation":"E-Invitation","filter-ig":"Filter Instagram","story":"Kisah Cinta","live-stream":"Live Streaming","private-invitation":"Personalized Invitation","event":"Sesi Acara","free-text":"Teks Gratis","event-count":"Jumlah Acara","story-count":"Jumlah Kisah Cinta","gallery-photo":"Galeri Foto","smart-wa":"Smart WhatsApp","manual-wa":"WhatsApp Manual","guest":"Tamu","gallery-video":"Video","music":"Lagu","template":"Kategori Desain","active":"Masa Aktif"}',
				'file'	    => 'package'
			]
        ]);
    }
}