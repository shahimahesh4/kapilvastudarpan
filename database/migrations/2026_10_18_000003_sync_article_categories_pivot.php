<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Populate article_category pivot table from existing articles with category_id
        $articles = DB::table('articles')
            ->whereNotNull('category_id')
            ->get(['id', 'category_id', 'created_at', 'updated_at']);

        $now = now();
        $records = [];
        foreach ($articles as $article) {
            $records[] = [
                'article_id' => $article->id,
                'category_id' => $article->category_id,
                'created_at' => $article->created_at ?? $now,
                'updated_at' => $article->updated_at ?? $now,
            ];
        }

        if (! empty($records)) {
            foreach (array_chunk($records, 100) as $chunk) {
                DB::table('article_category')->insertOrIgnore($chunk);
            }
        }
    }

    public function down(): void
    {
        // No down migration needed for sync data
    }
};
