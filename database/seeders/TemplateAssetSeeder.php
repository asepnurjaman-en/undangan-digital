<?php

namespace Database\Seeders;

use App\Models\TemplateAssets;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TemplateAssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TemplateAssets::insert([
            [
                'type'  => 'font',
                'title' => 'Caveat',
                'content' => 'Caveat',
                'publish' => 'publish',
                'user_id' => 1,
            ],
            [
                'type'  => 'font',
                'title' => 'Dancing Script',
                'content' => 'Dancing+Script',
                'publish' => 'publish',
                'user_id' => 1,
            ],
            [
                'type'  => 'font',
                'title' => 'Great Vibes',
                'content' => 'Great+Vibes',
                'publish' => 'publish',
                'user_id' => 1,
            ],
            [
                'type'  => 'font',
                'title' => 'Kaushan Script',
                'content' => 'Kaushan+Script',
                'publish' => 'publish',
                'user_id' => 1,
            ],
            [
                'type'  => 'font',
                'title' => 'Nova Cut',
                'content' => 'Nova+Cut',
                'publish' => 'publish',
                'user_id' => 1,
            ],
            [
                'type'  => 'font',
                'title' => 'Raleway',
                'content' => 'Raleway',
                'publish' => 'publish',
                'user_id' => 1,
            ],
            [
                'type'  => 'font',
                'title' => 'Righteous',
                'content' => 'Righteous',
                'publish' => 'publish',
                'user_id' => 1,
            ]
        ]);
    }
}
