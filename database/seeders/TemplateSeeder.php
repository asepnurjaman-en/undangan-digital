<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Template::insert([
			[
				'title'		=> 'Blue Splash',
				'slug'  	=> 'blue-splash',
				'preset'	=> '{"design":{"title":{"color":"#5576c3","font":"Dancing Script"},"content":{"color":"#7287a1","font":"Raleway"},"button":{"color":"#d9e1f2","background":"#061f5b"},"background":"#eaeef0","template":"1"},"cover":{"name":{"female":"Akhwat","male":"Ikhwan","size":"40","style":"stack"},"content":"Selamat datang","button":"Buka Undangan","description":{"top":"Assalamu`alaikum Warahmatullahi Wabarakatuh","bottom":"Wa`alaikumussalaam Warahmatullahi Wabarakatuh","image":{"method":"avatar","image":"couple.png"}}},"profile":{"instagram":{"male":"ikhwan","female":"akhwat","show":true},"parent":{"male":{"father":"nya","mother":"nya","childhood":"1"},"female":{"father":"nya","mother":"nya","childhood":"2"},"show":true},"name":{"male":"Ikhwan","female":"Akhwat"},"photo":{"male":{"method":"avatar","frame":null,"image":"9d348c30-9331-11ec-b089-ad70ef6b2563.png"},"female":{"method":"avatar","frame":null,"image":"4a1f7960-9331-11ec-8fa8-a3a23f6da840.png"}}},"detail":{"calendar":{"save":{"content":"Setel pengingat","show":false},"date":"2030-04-01","time":"08:30","timezone":"wib"},"countdown":{"show":false,"style":"stack"},"location":{"address":"Lokasi lengkap acara pernikahan","map":"https:\/\/map.google.com\/"},"additional":{"closing":"Terima kasih atas perhatiannya","special":["Raja Arab","Raja Qatar","Raja Bulan"],"show":false}},"quote":{"content":"Dan nikahkanlah orang-orang yang masih membujang di antara kamu, dan juga orang-orang yang layak (menikah) dari hamba-hamba sahayamu yang laki-laki dan perempuan. Jika mereka miskin, Allah akan memberi kemampuan kepada mereka dengan karunia-Nya."},"music":{"title":"Natta Reza - Kekasih Impian","url":"http:\/\/127.0.0.1:8000\/storage\/audio\/nattareza.mp3","show":true},"rsvp":{"title":"Kami tunggu kedatangannya","content":"Beli buah ke pasar baru, Nantikan acara pernikahan kami yg sangat seruuuu","date":"2029-04-01","yes":{"option":"Insyaallah","content":"Syukron sahabat"},"no":{"option":"Afwan Bestie","content":"Gapapa Wahai sahabat"}},"additional":{"live":{"app":"zoom","link":"https:\/\/zoom.live","content":"Ayok nobar","show":false},"protocol":{"code":"23","show":false,"title":"Health Protocol","content":"To all beloved invited guests, are expected to obey"}},"gift":{"show":false,"title":"Amplop Digital","content":"Donasi yang terkumpul akan kami donasikan pada Yayasan yatim piatu","bank":{"name":"Yayasan","code":"123456789","option":"bsi"}},"wishes":{"title":"Kirim Ucapan","content":"Terima kasih","public":false}}',
				'file'		=> '3WFqZTyop3fO6uxGwqIsMqJwVKJBwBuWyJEEQKl0.jpg',
				'url'	    => 'default',
				'grade'	    => 'basic',
				'publish'	=> 'publish'
			]
        ]);
    }
}
