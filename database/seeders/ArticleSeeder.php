<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [];

        for ($i = 1; $i <= 10; $i++) {
            $isPublished = fake()->boolean(70); // 70% published

            $articles[] = [
                'title' => fake()->sentence(6),
                'excerpt' => fake()->optional()->text(150),
                'content' => fake()->paragraphs(5, true),
                'featured_image' => fake()->optional()->imageUrl(800, 600, 'business'),
                'status' => $isPublished,
                'published_at' => $isPublished ? Carbon::now()->subDays(rand(1, 30)) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('articles')->insert($articles);
    }
}