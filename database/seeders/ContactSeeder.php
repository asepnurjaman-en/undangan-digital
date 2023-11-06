<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Contact::insert([
			[
				'type'		=> 'address',
				'title' 	=> 'Alamat',
				'content'	=> 'Ketikan alamat lengkap kamu disini, lebih akurat lebih baik.',
				'pinned'	=> '0',
				'actived'	=> '1',
				'user_id'	=> 1
			],
			[
				'type'		=> 'map',
				'title' 	=> 'Peta',
				'content'	=> '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15841.094076853615!2d107.6807385!3d-6.9770199999999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68c3bece0e7279%3A0xa6a19940ecd6accc!2sCV.%20Bio%20Cipta%20Mandiri!5e0!3m2!1sid!2sid!4v1677656611142!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
				'pinned'	=> '0',
				'actived'	=> '1',
				'user_id'	=> 1
			],
			[
				'type'		=> 'phone',
				'title' 	=> 'Telepon',
				'content'	=> '08100000000',
				'pinned'	=> '0',
				'actived'	=> '1',
				'user_id'	=> 1
			],
			[
				'type'		=> 'phone',
				'title' 	=> 'SMS',
				'content'	=> '08100000000',
				'pinned'	=> '0',
				'actived'	=> '1',
				'user_id'	=> 1
			],
			[
				'type'		=> 'whatsapp',
				'title' 	=> 'Admin',
				'content'	=> '08100000000',
				'pinned'	=> '0',
				'actived'	=> '1',
				'user_id'	=> 1
			],
			[
				'type'		=> 'whatsapp',
				'title' 	=> 'Finance',
				'content'	=> '08100000000',
				'pinned'	=> '0',
				'actived'	=> '1',
				'user_id'	=> 1
			],
			[
				'type'		=> 'whatsapp',
				'title' 	=> 'Consultant',
				'content'	=> '08100000000',
				'pinned'	=> '0',
				'actived'	=> '1',
				'user_id'	=> 1
			],
			[
				'type'		=> 'email',
				'title' 	=> 'E-Mail',
				'content'	=> 'email@email.com',
				'pinned'	=> '0',
				'actived'	=> '1',
				'user_id'	=> 1
			],
			[
				'type'		=> 'email',
				'title' 	=> 'Web Mail',
				'content'	=> 'email@webmail.com',
				'pinned'	=> '0',
				'actived'	=> '1',
				'user_id'	=> 1
			],
			[
				'type'		=> 'email',
				'title' 	=> 'Any Mail',
				'content'	=> 'email@anymail.com',
				'pinned'	=> '0',
				'actived'	=> '1',
				'user_id'	=> 1
			]
		]);
    }
}
