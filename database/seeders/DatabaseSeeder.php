<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@kapilvastudarpan.com'],
            [
                'name' => 'कपिलवस्तु दर्पण एडमिन',
                'password' => bcrypt('password'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        $this->call([
            RatopatiNewsSeeder::class,
            AdvertisementSeeder::class,
            MoreArticlesSeeder::class,
        ]);
    }
}
