<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('article_category')) {
            Schema::create('article_category', function (Blueprint $table) {
                $table->id();
                $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
                $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['article_id', 'category_id']);
            });
        }

        Schema::table('articles', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->change();
        });

        // Backfill existing relationships
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
        Schema::dropIfExists('article_category');

        Schema::table('articles', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable(false)->change();
        });
    }
};
