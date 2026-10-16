<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use Illuminate\Database\Seeder;

class AdvertisementSeeder extends Seeder
{
    public function run(): void
    {
        $ads = [
            [
                'title' => 'कपिलवस्तु दर्पण डिजिटल विज्ञापन सेवा',
                'placement' => 'header',
                'type' => 'image',
                'image_path' => 'images/ads/banner-1.gif',
                'target_url' => 'https://www.kapilvastudarpan.com',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'title' => 'टाटा मोटर्स धनतेरस विशेष अफर',
                'placement' => 'home_top',
                'type' => 'image',
                'image_path' => 'images/ads/banner-4.jpg',
                'target_url' => 'https://www.tatamotors.com.np',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'title' => 'नेपाल टेलिकम एफटीटीएच फाइबर अफर',
                'placement' => 'home_middle',
                'type' => 'image',
                'image_path' => 'images/ads/banner-2.gif',
                'target_url' => 'https://ntc.net.np',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'title' => 'लुम्बिनी बैंक सुपर सेभिङ्स खाता',
                'placement' => 'sidebar',
                'type' => 'image',
                'image_path' => 'images/ads/banner-3.gif',
                'target_url' => 'https://www.kapilvastudarpan.com',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'title' => 'सामसुङ ग्यालेक्सी फेस्टिभ अफर',
                'placement' => 'article_incontent',
                'type' => 'image',
                'image_path' => 'images/ads/banner-1.gif',
                'target_url' => 'https://www.kapilvastudarpan.com',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'title' => 'कपिलवस्तु मल्टिपर्पोज मिडिया प्रवर्द्धन',
                'placement' => 'footer',
                'type' => 'image',
                'image_path' => 'images/ads/banner-2.gif',
                'target_url' => 'https://www.kapilvastudarpan.com',
                'is_active' => true,
                'order' => 1,
            ],
        ];

        foreach ($ads as $ad) {
            Advertisement::updateOrCreate(
                ['title' => $ad['title'], 'placement' => $ad['placement']],
                $ad
            );
        }
    }
}
