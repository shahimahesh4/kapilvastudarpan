<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password'),
        ]);

        $categories = collect([
            'मुख्य समाचार' => 'main-news', 'कपिलवस्तु विशेष' => 'kapilvastu-special', 'राजनीति' => 'politics',
            'अर्थ' => 'economy', 'समाज' => 'society', 'शिक्षा' => 'education', 'खेलकुद' => 'sports',
        ])->mapWithKeys(fn (string $slug, string $name) => [$name => Category::updateOrCreate(['slug' => $slug], ['name' => $name])]);

        $stories = [
            ['kapilvastu-office', 'कपिलवस्तुमा सार्वजनिक सेवाको पहुँच विस्तार गर्ने तयारी', 'कपिलवस्तु विशेष', 'लुम्बिनी प्रदेश'],
            ['constitution-amendment', 'संविधान संशोधनबारे जनमोर्चाको नयाँ प्रस्ताव सार्वजनिक', 'राजनीति', 'बागमती प्रदेश'],
            ['madhesh-agriculture', 'मधेश प्रदेशमा कृषि उत्पादन बढाउन विशेष कार्यक्रम सुरु', 'अर्थ', 'मधेश प्रदेश'],
            ['bagmati-budget', 'बागमती प्रदेशमा विकास बजेट कार्यान्वयनलाई तीव्रता दिने तयारी', 'राजनीति', 'बागमती प्रदेश'],
            ['koshi-road', 'कोशी प्रदेशमा सडक सञ्जाल विस्तारको योजना अघि बढ्यो', 'मुख्य समाचार', 'कोशी प्रदेश'],
            ['gandaki-tourism', 'गण्डकीमा पर्यटन र रोजगारी प्रवर्द्धनको नयाँ योजना', 'अर्थ', 'गण्डकी प्रदेश'],
            ['lumbini-health', 'लुम्बिनीमा स्वास्थ्य सेवाको पहुँच विस्तार गर्ने तयारी', 'समाज', 'लुम्बिनी प्रदेश'],
            ['karnali-connectivity', 'कर्णालीमा दुर्गम बस्ती जोड्ने सडक निर्माण सुरु', 'मुख्य समाचार', 'कर्णाली प्रदेश'],
            ['far-west-education', 'सुदूरपश्चिममा शिक्षा र स्वास्थ्यमा लगानी बढाइँदै', 'शिक्षा', 'सुदूरपश्चिम प्रदेश'],
            ['campus-fee-review', 'सिद्धार्थ क्याम्पसमा शुल्क वृद्धि पुनरावलोकनको माग', 'शिक्षा', 'लुम्बिनी प्रदेश'],
            ['national-bank-board', 'राष्ट्रिय वाणिज्य बैंकमा नयाँ सञ्चालक नियुक्त', 'अर्थ', 'बागमती प्रदेश'],
            ['wheelchair-rugby', 'तीन दिने ह्विलचेयर रग्बी प्रशिक्षण सम्पन्न', 'खेलकुद', 'लुम्बिनी प्रदेश'],
        ];

        foreach ($stories as [$slug, $title, $category, $province]) {
            Article::updateOrCreate(['slug' => $slug], [
                'category_id' => $categories[$category]->id,
                'title' => $title,
                'excerpt' => 'स्थानीय समुदाय, विकास र जनजीवनसँग जोडिएको विस्तृत समाचार र विश्लेषण।',
                'body' => $title.' सम्बन्धी विस्तृत समाचार सामग्री यहाँ प्रस्तुत गरिएको छ।',
                'author' => 'कपिलवस्तु दर्पण',
                'province' => $province,
                'published_at' => now()->subMinutes(array_search($slug, array_column($stories, 0)) * 15),
                'is_featured' => in_array($slug, ['kapilvastu-office', 'constitution-amendment'], true),
            ]);
        }
    }
}
