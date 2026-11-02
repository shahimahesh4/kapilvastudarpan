<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $now = now();

        // Keep the two district categories together near the front of navigation.
        if (! DB::table('categories')->where('slug', 'rupandehi-special')->exists()) {
            DB::table('categories')->where('order', '>=', 3)->increment('order');
        }

        foreach ([
            [
                'name' => 'कपिलवस्तु विशेष',
                'slug' => 'kapilvastu-special',
                'description' => 'कपिलवस्तु जिल्लासँग सम्बन्धित ताजा समाचार, स्थानीय सरोकार र विशेष कभरेज।',
                'order' => 2,
            ],
            [
                'name' => 'रुपन्देही विशेष',
                'slug' => 'rupandehi-special',
                'description' => 'रुपन्देही जिल्लासँग सम्बन्धित ताजा समाचार, स्थानीय सरोकार र विशेष कभरेज।',
                'order' => 3,
            ],
        ] as $category) {
            $values = [
                ...$category,
                'is_nav' => true,
                'drawer_icon' => 'local',
                'meta_title' => $category['name'].' | कपिलवस्तु दर्पण',
                'meta_description' => $category['description'],
                'meta_keywords' => $category['name'].', स्थानीय समाचार, कपिलवस्तु दर्पण',
                'updated_at' => $now,
            ];

            $existing = DB::table('categories')->where('slug', $category['slug']);

            if ($existing->exists()) {
                $existing->update($values);
            } else {
                DB::table('categories')->insert([...$values, 'created_at' => $now]);
            }
        }
    }

    public function down(): void
    {
        DB::table('categories')->whereIn('slug', ['kapilvastu-special', 'rupandehi-special'])->delete();
    }
};
