<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General / Portal
            'site_name' => 'कपिलवस्तु दर्पण',
            'site_subbadge' => 'डिजिटल न्युज',
            'site_tagline' => 'सत्य, तथ्य र निष्पक्ष पत्रकारिता — लुम्बिनी प्रदेशको सशक्त डिजिटल आवाज',
            'footer_about' => 'कपिलवस्तु दर्पण स्थानीय सरोकार, जनजीवन, विकास र राष्ट्रिय सवालहरूलाई जिम्मेवार र तथ्यपूर्ण ढङ्गले प्रस्तुत गर्ने आधिकारिक डिजिटल समाचार पोर्टल हो।',
            'site_logo' => 'settings/kapilvastu-darpan-logo.png',
            'site_favicon' => 'settings/favicon.png',
            'news_profile_photo' => 'settings/profile-avatar.png',
            'footer_copyright' => 'कपिलवस्तु दर्पण न्यूज - कपिलवस्तु मल्टिपर्पोज मिडिया प्रा.लि. सर्वाधिकार सुरक्षित।',
            'homepage_featured_rows' => 1,

            // Contact & Office
            'contact_email' => 'kapilvastudarpannews@gmail.com',
            'contact_phone' => '+९७७-९७६६२५०४४५',
            'contact_hotline' => '९८१६४०८८७३',
            'website_url' => 'https://kapilvastudarpan.com',
            'contact_address' => 'कपिलवस्तु–१०, पचेहरा, लुम्बिनी प्रदेश, नेपाल',
            'office_hours' => 'आइतबार – शुक्रबार, बिहान ९:०० देखि साँझ ६:०० बजेसम्म',

            // Editorial & Legal Registration
            'company_name' => 'कपिलवस्तु मल्टिपर्पोज मिडिया प्रा.लि.',
            'doi_reg_no' => '५३१४/८२/८३',
            'company_reg_no' => '३१७९१९/८२/८३',
            'press_council_no' => '२०८२/८३',
            'press_council_badge' => 'आधिकारिक',
            'operator_name' => 'तारानाथ केवट',
            'operator_photo' => 'team/taranath-kewat.png',
            'editor_name' => 'टुकलाल गैरे',
            'editor_photo' => 'team/tuklal-gaire.png',
            'managing_director' => 'शेषकान्त मरासिनी',
            'managing_director_photo' => 'team/sheskant-marasini.png',
            'executive_editor' => '',
            'executive_editor_photo' => '',
            'news_chief' => '',
            'news_chief_photo' => '',
            'bureau_chief' => '',
            'bureau_chief_photo' => '',
            'legal_advisor' => 'अधिवक्ता राम कुमार शर्मा',
            'additional_team_members' => [],
            'show_article_editor_box' => false,
            'show_article_view_count' => true,

            // Social Media Links
            'social_facebook' => 'https://facebook.com/kapilvastudarpan',
            'social_twitter' => 'https://x.com/kapilvastu_d',
            'social_instagram' => 'https://instagram.com/kapilvastudarpan',
            'social_youtube' => 'https://youtube.com/@kapilvastudarpan',
            'social_tiktok' => 'https://tiktok.com/@kapilvastudarpan',
            'social_whatsapp' => '+९७७-९७६६२५०४४५',

            // Video & YouTube API Settings
            'show_video_section' => true,
            'video_section_title' => 'भिडियो विशेष',
            'youtube_api_enabled' => false,
            'youtube_api_key' => '',
            'youtube_channel_id' => '',
            'youtube_playlist_id' => '',
            'youtube_featured_video_url' => '',
            'youtube_cache_minutes' => 60,

            // Rashifal (Horoscope)
            'show_rashifal_section' => true,
            'rashifal_section_title' => 'दैनिक राशिफल',
            'rashifal_section_subtitle' => 'आजको ग्रहगोचर तथा १२ राशिको विस्तृत फलादेश',

            // User Auth & Email Verification
            'enable_email_verification' => false,

            // Push Notifications & ShareMarket
            'push_notifications_enabled' => true,
            'sharemarket_enabled' => true,
            'sharemarket_ticker_enabled' => true,

            // SEO
            'meta_title' => 'कपिलवस्तु दर्पण | सत्य, तथ्य र निष्पक्ष पत्रकारिता — लुम्बिनी प्रदेशको सशक्त आवाज',
            'meta_description' => 'कपिलवस्तु दर्पण स्थानीय सरोकार, जनजीवन, विकास र राष्ट्रिय सवालहरूलाई जिम्मेवार र तथ्यपूर्ण ढङ्गले प्रस्तुत गर्ने आधिकारिक डिजिटल समाचार पोर्टल हो।',
            'meta_keywords' => 'कपिलवस्तु दर्पण, ताजा समाचार, लुम्बिनी समाचार, नेपाल समाचार, kapilvastu darpan',
            'header_scripts' => '',

            // Maintenance Mode
            'maintenance_mode_enabled' => false,
            'maintenance_secret' => 'darpan-live-bypass',
            'maintenance_message' => 'आदरणीय पाठकवृन्द, तपाईंहरूलाई अझ स्तरीय, सुरक्षित र द्रुत गतिको समाचार वाचन अनुभव प्रदान गर्नका लागि अहिले प्राविधिक मर्मत तथा प्रणाली स्तरोन्नतिको कार्य भइरहेको छ। केही बेरमै हामी पुनः प्रत्यक्ष प्रसारणमा आउनेछौं।',
            'maintenance_estimated_time' => 'केही बेरमा (Shortly)',
        ];

        Setting::setMany($settings);
    }
}
