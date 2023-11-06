<?php

namespace Database\Seeders;

use App\Models\LinkExternal;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LinkExSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LinkExternal::insert([
			[
				'type'		=> 'social',
				'brand'		=> 'discord',
				'title' 	=> 'Discord',
				'url'		=> 'https://discord.com/',
				'icon'		=> 'bx bxl-discord',
				'actived'	=> '0',
				'user_id'	=> 1
			],
			[
				'type'		=> 'social',
				'brand'		=> 'facebook',
				'title' 	=> 'FB',
				'url'		=> 'https://facebook.com/',
				'icon'		=> 'bx bxl-facebook',
				'actived'	=> '0',
				'user_id'	=> 1
			],
			[
				'type'		=> 'social',
				'brand'		=> 'instagram',
				'title' 	=> 'IG',
				'url'		=> 'https://instagram.com/',
				'icon'		=> 'bx bxl-instagram',
				'actived'	=> '0',
				'user_id'	=> 1
			],
			[
				'type'		=> 'social',
				'brand'		=> 'linkedIn',
				'title' 	=> 'LIn',
				'url'		=> 'https://linkedIn.com/',
				'icon'		=> 'bx bxl-linkedin',
				'actived'	=> '0',
				'user_id'	=> 1
			],
			[
				'type'		=> 'social',
				'brand'		=> 'pinterest',
				'title' 	=> 'Pin',
				'url'		=> 'https://pinterest.com/',
				'icon'		=> 'bx bxl-pinterest',
				'actived'	=> '0',
				'user_id'	=> 1
			],
			[
				'type'		=> 'social',
				'brand'		=> 'snapchat',
				'title' 	=> 'Snap',
				'url'		=> 'https://snapchat.com/',
				'icon'		=> 'bx bxl-snapchat',
				'actived'	=> '0',
				'user_id'	=> 1
			],
			[
				'type'		=> 'social',
				'brand'		=> 'telegram',
				'title' 	=> 'Tel',
				'url'		=> 'https://telegram.com/',
				'icon'		=> 'bx bxl-telegram',
				'actived'	=> '0',
				'user_id'	=> 1
			],
			[
				'type'		=> 'social',
				'brand'		=> 'tiktok',
				'title' 	=> 'Tiktok',
				'url'		=> 'https://tiktok.com/',
				'icon'		=> 'bx bxl-tiktok',
				'actived'	=> '0',
				'user_id'	=> 1
			],
			[
				'type'		=> 'social',
				'brand'		=> 'twitch',
				'title' 	=> 'Twitch',
				'url'		=> 'https://twitch.com/',
				'icon'		=> 'bx bxl-twitch',
				'actived'	=> '0',
				'user_id'	=> 1
			],
			[
				'type'		=> 'social',
				'brand'		=> 'twitter',
				'title' 	=> 'Twitter',
				'url'		=> 'https://twitter.com/',
				'icon'		=> 'bx bxl-twitter',
				'actived'	=> '0',
				'user_id'	=> 1
			],
			[
				'type'		=> 'social',
				'brand'		=> 'whatsapp',
				'title' 	=> 'WA',
				'url'		=> 'https://whatsapp.com/',
				'icon'		=> 'bx bxl-whatsapp',
				'actived'	=> '0',
				'user_id'	=> 1
			],
			[
				'type'		=> 'social',
				'brand'		=> 'youtube',
				'title' 	=> 'YT',
				'url'		=> 'https://youtube.com/',
				'icon'		=> 'bx bxl-youtube',
				'actived'	=> '0',
				'user_id'	=> 1
			],
			[
				'type'		=> 'ecommerce',
				'brand'		=> 'blibli',
				'title' 	=> 'blibli',
				'url'		=> 'https://blibli.com/',
				'icon'		=> 'blibli.png',
				'actived'	=> '0',
				'user_id'	=> 1
			],
			[
				'type'		=> 'ecommerce',
				'brand'		=> 'bukalapak',
				'title' 	=> 'bukalapak',
				'url'		=> 'https://bukalapak.com/',
				'icon'		=> 'bukalapak.png',
				'actived'	=> '0',
				'user_id'	=> 1
			],
			[
				'type'		=> 'ecommerce',
				'brand'		=> 'jdid',
				'title' 	=> 'jdid',
				'url'		=> 'https://jd.id/',
				'icon'		=> 'jdid.png',
				'actived'	=> '0',
				'user_id'	=> 1
			],
			[
				'type'		=> 'ecommerce',
				'brand'		=> 'lazada',
				'title' 	=> 'lazada',
				'url'		=> 'https://lazada.com/',
				'icon'		=> 'lazada.png',
				'actived'	=> '0',
				'user_id'	=> 1
			],
			[
				'type'		=> 'ecommerce',
				'brand'		=> 'shopee',
				'title' 	=> 'shopee',
				'url'		=> 'https://shopee.com/',
				'icon'		=> 'shopee.png',
				'actived'	=> '0',
				'user_id'	=> 1
			],
			[
				'type'		=> 'ecommerce',
				'brand'		=> 'tiktokshop',
				'title' 	=> 'tiktokshop',
				'url'		=> 'https://tiktokshop.com/',
				'icon'		=> 'tiktokshop.png',
				'actived'	=> '0',
				'user_id'	=> 1
			],
			[
				'type'		=> 'ecommerce',
				'brand'		=> 'tokopedia',
				'title' 	=> 'tokopedia',
				'url'		=> 'https://tokopedia.com/',
				'icon'		=> 'tokopedia.png',
				'actived'	=> '0',
				'user_id'	=> 1
			],
			[
				'type'		=> 'ecommerce',
				'brand'		=> 'zalora',
				'title' 	=> 'zalora',
				'url'		=> 'https://zalora.com/',
				'icon'		=> 'zalora.png',
				'actived'	=> '0',
				'user_id'	=> 1
			]
		]);
    }
}
