<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        if (MenuItem::count() > 0) {
            return;
        }

        $cats = Category::all()->keyBy('slug');

        $items = [
            ['title' => 'मुख्य समाचार', 'slug' => 'main-news', 'order' => 1],
            ['title' => 'कपिलवस्तु विशेष', 'slug' => 'kapilvastu-special', 'order' => 2],
            ['title' => 'राजनीति', 'slug' => 'politics', 'order' => 3],
            ['title' => 'अर्थतन्त्र', 'slug' => 'economy', 'order' => 4],
            ['title' => 'प्रदेश', 'slug' => 'pradesh', 'order' => 5, 'children' => [
                ['title' => 'लुम्बिनी प्रदेश', 'url' => '/category/pradesh', 'order' => 1],
                ['title' => 'बागमती प्रदेश', 'url' => '/category/pradesh', 'order' => 2],
                ['title' => 'कोशी प्रदेश', 'url' => '/category/pradesh', 'order' => 3],
                ['title' => 'गण्डकी प्रदेश', 'url' => '/category/pradesh', 'order' => 4],
                ['title' => 'मधेश प्रदेश', 'url' => '/category/pradesh', 'order' => 5],
                ['title' => 'कर्णाली प्रदेश', 'url' => '/category/pradesh', 'order' => 6],
                ['title' => 'सुदूरपश्चिम प्रदेश', 'url' => '/category/pradesh', 'order' => 7],
            ]],
            ['title' => 'समाज', 'slug' => 'society', 'order' => 6],
            ['title' => 'अपराध-सुरक्षा', 'slug' => 'crime-security', 'order' => 7],
            ['title' => 'खेलकुद', 'slug' => 'sports', 'order' => 8],
            ['title' => 'विचार', 'slug' => 'opinion', 'order' => 9],
            ['title' => 'स्वास्थ्य', 'slug' => 'health', 'order' => 10],
        ];

        foreach ($items as $data) {
            $cat = isset($data['slug']) ? ($cats[$data['slug']] ?? null) : null;

            $parent = MenuItem::create([
                'title' => $data['title'],
                'type' => $data['type'] ?? ($cat ? 'category' : 'custom'),
                'category_id' => $cat?->id,
                'url' => $data['url'] ?? null,
                'location' => 'header',
                'target' => '_self',
                'order' => $data['order'],
                'is_active' => true,
            ]);

            if (!empty($data['children'])) {
                foreach ($data['children'] as $child) {
                    MenuItem::create([
                        'title' => $child['title'],
                        'type' => 'custom',
                        'url' => $child['url'],
                        'parent_id' => $parent->id,
                        'location' => 'header',
                        'target' => '_self',
                        'order' => $child['order'],
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
