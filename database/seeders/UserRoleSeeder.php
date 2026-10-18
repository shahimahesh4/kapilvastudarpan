<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Seed users for each role.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'कपिलवस्तु दर्पण मुख्य प्रशासक (Super Admin)',
                'email' => 'admin@kapilvastudarpan.com',
                'role' => User::ROLE_SUPER_ADMIN,
                'password' => Hash::make('password'),
                'phone' => '+977 9857000001',
                'bio' => 'Main super administrator with full system authority, configuration controls, and role assignment capabilities.',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'दैनिक प्रशासक (Portal Administrator)',
                'email' => 'administrator@kapilvastudarpan.com',
                'role' => User::ROLE_ADMIN,
                'password' => Hash::make('Admin@123#Kpd'),
                'phone' => '+977 9857000002',
                'bio' => 'Portal administrator responsible for daily operational oversight, user management, and settings.',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'सम्पादक (Chief News Editor)',
                'email' => 'editor@kapilvastudarpan.com',
                'role' => User::ROLE_EDITOR,
                'password' => Hash::make('Editor@123#Kpd'),
                'phone' => '+977 9857000003',
                'bio' => 'Senior news editor responsible for publishing, reviewing all articles, managing categories, and moderating reader comments.',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'संवाददाता (Staff Reporter & Publisher)',
                'email' => 'reporter@kapilvastudarpan.com',
                'role' => User::ROLE_PUBLISHER,
                'password' => Hash::make('Reporter@123#Kpd'),
                'phone' => '+977 9857000004',
                'bio' => 'News reporter who writes and publishes field reports, news updates, and local stories.',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'दर्ता सदस्य (Subscribed Reader & Member)',
                'email' => 'reader@kapilvastudarpan.com',
                'role' => User::ROLE_SUBSCRIBER,
                'password' => Hash::make('Reader@123#Kpd'),
                'phone' => '+977 9857000005',
                'bio' => 'Subscribed community member with access to front-end profiles, commenting, and bookmarking.',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
