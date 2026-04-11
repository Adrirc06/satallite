<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $article = Article::create([
            'title' => 'Prueba de artículo 1',
            'subtitle' => 'Subtítulo del artículo 1',
            'banner_url' => 'https://example.com/banner1.jpg',
            'user_id' => 1,
            'content' => 'Contenido del artículo 1',
        ]);
    }
}
