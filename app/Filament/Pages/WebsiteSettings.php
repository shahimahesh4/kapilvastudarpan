<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class WebsiteSettings extends Page
{
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Website Settings';

    protected static ?string $title = 'Website Settings';

    protected static ?int $navigationSort = 10;

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    protected string $view = 'filament.pages.website-settings';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $settings = Setting::getAll();

        $this->form->fill($settings);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Tabs::make('SettingsTabs')
                    ->tabs([
                        Tab::make('General')
                            ->label('General / Portal')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                Section::make('Portal Identity')
                                    ->description('Basic website name, slogan, badge and image assets.')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            TextInput::make('site_name')
                                                ->label('Portal Name (पोर्टलको नाम)')
                                                ->required()
                                                ->placeholder('कपिलवस्तु दर्पण'),

                                            TextInput::make('site_subbadge')
                                                ->label('Sub-Badge (उप-शीर्षक ब्याच)')
                                                ->placeholder('डिजिटल न्युज')
                                                ->helperText('Displayed next to logo in red pill badge'),

                                            TextInput::make('site_tagline')
                                                ->label('Slogan / Tagline (मूल नारा)')
                                                ->placeholder('सत्य, तथ्य र निष्पक्ष पत्रकारिता...'),
                                        ]),

                                        Textarea::make('footer_about')
                                            ->label('Portal Description / About (पोर्टल परिचय / विवरण)')
                                            ->rows(3)
                                            ->placeholder('कपिलवस्तु दर्पण स्थानीय सरोकार, जनजीवन, विकास र राष्ट्रिय सवालहरूलाई जिम्मेवार र तथ्यपूर्ण ढङ्गले प्रस्तुत गर्ने आधिकारिक डिजिटल समाचार पोर्टल हो।')
                                            ->helperText('Shown in footer column 1 about text block.')
                                            ->columnSpanFull(),

                                        Grid::make(3)->schema([
                                            FileUpload::make('site_logo')
                                                ->label('Logo Image (लोगो फोटो अपलोड)')
                                                ->image()
                                                ->disk('public')
                                                ->directory('settings')
                                                ->maxSize(5120)
                                                ->helperText('PNG / JPG / SVG logo file - क्लिक वा ड्र्याग गरेर नयाँ लोगो अपलोड गर्नुहोस्।'),

                                            FileUpload::make('site_favicon')
                                                ->label('Favicon Image (फेविकन फोटो अपलोड)')
                                                ->image()
                                                ->disk('public')
                                                ->directory('settings')
                                                ->maxSize(2048)
                                                ->helperText('Square (1:1) PNG or ICO favicon - क्लिक वा ड्र्याग गरेर नयाँ फेविकन अपलोड गर्नुहोस्।'),

                                            FileUpload::make('news_profile_photo')
                                                ->label('News Profile Photo (न्युज प्रोफाइल फोटो)')
                                                ->image()
                                                ->disk('public')
                                                ->directory('settings')
                                                ->imageCropAspectRatio('1:1')
                                                ->maxSize(2048)
                                                ->helperText('वृत्ताकार/Square (1:1) न्युज प्रोफाइल फोटो — लेखक विवरणमा देखिने।'),
                                        ]),

                                        TextInput::make('footer_copyright')
                                            ->label('Footer Copyright Notice')
                                            ->placeholder('कपिलवस्तु दर्पण न्यूज - सर्वाधिकार सुरक्षित।')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tab::make('Contact')
                            ->label('Contact Info')
                            ->icon('heroicon-o-chat-bubble-left-right')
                            ->schema([
                                Section::make('General Configuration')
                                    ->description('Primary contact details for the portal and public contact page.')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            TextInput::make('contact_email')
                                                ->label('Portal Email')
                                                ->email()
                                                ->required()
                                                ->placeholder('kapilvastudarpannews@gmail.com'),

                                            TextInput::make('contact_phone')
                                                ->label('Portal Phone')
                                                ->required()
                                                ->placeholder('+९७७-९७६६२५०४४५'),

                                            TextInput::make('website_url')
                                                ->label('Official Website URL (वेबसाइट ठेगाना)')
                                                ->placeholder('https://kapilvastudarpan.com')
                                                ->helperText('Displayed in footer next to 🌐 icon and on contact page.'),
                                        ]),

                                        Grid::make(2)->schema([
                                            TextInput::make('contact_hotline')
                                                ->label('Hotline / Secondary Phone')
                                                ->placeholder('९८१६४०८८७३'),

                                            TextInput::make('office_hours')
                                                ->label('Office Operating Hours')
                                                ->placeholder('आइतबार – शुक्रबार, बिहान ९:०० देखि साँझ ६:०० बजेसम्म'),
                                        ]),

                                        Textarea::make('contact_address')
                                            ->label('Portal Physical Address')
                                            ->rows(3)
                                            ->required()
                                            ->placeholder('कपिलवस्तु १०, पचेहरा (तौलिहवा), लुम्बिनी प्रदेश, नेपाल')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tab::make('Editorial')
                            ->label('Editorial & Registration')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make('Company & Legal Registrations')
                                    ->description('Official registration credentials and publisher details.')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('company_name')
                                                ->label('Publishing Company (सञ्चालक संस्था)')
                                                ->placeholder('कपिलवस्तु मल्टिपर्पोज मिडिया प्रा.लि.'),

                                            TextInput::make('doi_reg_no')
                                                ->label('Dept. of Information Reg No (सूचना विभाग दर्ता नं)')
                                                ->placeholder('५३१४/८२/८३'),
                                        ]),

                                        Grid::make(3)->schema([
                                            TextInput::make('company_reg_no')
                                                ->label('Company Registrar Reg No (कम्पनी दर्ता नं)')
                                                ->placeholder('३१७९१९/८२/८३'),

                                            TextInput::make('press_council_no')
                                                ->label('Press Council Nepal Listing (प्रेस काउन्सिल सूचीकरण)')
                                                ->placeholder('२०८२/८३'),

                                            TextInput::make('press_council_badge')
                                                ->label('Press Council Status Badge (प्रेस काउन्सिल स्थिति)')
                                                ->placeholder('आधिकारिक')
                                                ->helperText('Shown in badge next to council number (e.g. आधिकारिक)'),
                                        ]),
                                    ]),

                                Section::make('Core Leadership (सम्पादकीय नेतृत्व)')
                                    ->description('Top executive and editorial leadership (operator, editor-in-chief, managing director).')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            TextInput::make('operator_name')
                                                ->label('Operator / Chairman (संचालक)')
                                                ->placeholder('तारानाथ केवट'),

                                            TextInput::make('editor_name')
                                                ->label('Editor-in-Chief (सम्पादक)')
                                                ->placeholder('टुकलाल गैरे'),

                                            TextInput::make('managing_director')
                                                ->label('Managing Director (प्रबन्ध निर्देशक)')
                                                ->placeholder('शेषकान्त मरासिनी'),
                                        ]),

                                        Grid::make(3)->schema([
                                            FileUpload::make('operator_photo')
                                                ->label('Operator Passport Photo (संचालक फोटो अपलोड)')
                                                ->image()
                                                ->disk('public')
                                                ->directory('team')
                                                ->imageEditor()
                                                ->imageCropAspectRatio('3.5:4.5')
                                                ->maxSize(5120)
                                                ->helperText('Passport size 3.5:4.5 — Drag & drop or browse from computer/phone.'),

                                            FileUpload::make('editor_photo')
                                                ->label('Editor Passport Photo (सम्पादक फोटो अपलोड)')
                                                ->image()
                                                ->disk('public')
                                                ->directory('team')
                                                ->imageEditor()
                                                ->imageCropAspectRatio('3.5:4.5')
                                                ->maxSize(5120)
                                                ->helperText('Passport size 3.5:4.5 — Drag & drop or browse from computer/phone.'),

                                            FileUpload::make('managing_director_photo')
                                                ->label('Managing Director Photo (प्रबन्ध निर्देशक फोटो अपलोड)')
                                                ->image()
                                                ->disk('public')
                                                ->directory('team')
                                                ->imageEditor()
                                                ->imageCropAspectRatio('3.5:4.5')
                                                ->maxSize(5120)
                                                ->helperText('Passport size 3.5:4.5 — Drag & drop or browse from computer/phone.'),
                                        ]),
                                        Toggle::make('show_article_editor_box')
                                            ->label('Show Editor Bio Box on Article Pages (समाचार विवरण पृष्ठमा सम्पादकीय परिचय कार्ड देखाउने)')
                                            ->helperText('बन्द (disabled) गर्दा समाचार पृष्ठको अन्त्यमा देखिने सम्पादकीय परिचय कार्ड लुक्नेछ (पूर्वनिर्धारित: बन्द)।')
                                            ->default(false),
                                    ]),

                                Section::make('Extended Leadership & Advisory (थप सम्पादकीय तथा सल्लाहकार)')
                                    ->description('Executive editor, news chief, bureau chief, and legal advisor.')
                                    ->collapsible()
                                    ->schema([
                                        Grid::make(3)->schema([
                                            TextInput::make('executive_editor')
                                                ->label('Executive Editor (कार्यकारी सम्पादक)')
                                                ->placeholder('कार्यकारी सम्पादकको नाम'),

                                            TextInput::make('news_chief')
                                                ->label('News Chief (समाचार प्रमुख)')
                                                ->placeholder('समाचार प्रमुखको नाम'),

                                            TextInput::make('bureau_chief')
                                                ->label('Bureau Chief (ब्युरो प्रमुख)')
                                                ->placeholder('ब्युरो प्रमुखको नाम'),
                                        ]),

                                        Grid::make(3)->schema([
                                            FileUpload::make('executive_editor_photo')
                                                ->label('Executive Editor Photo (कार्यकारी सम्पादक फोटो)')
                                                ->image()
                                                ->disk('public')
                                                ->directory('team')
                                                ->imageEditor()
                                                ->imageCropAspectRatio('3.5:4.5')
                                                ->maxSize(5120)
                                                ->helperText('Passport size 3.5:4.5'),

                                            FileUpload::make('news_chief_photo')
                                                ->label('News Chief Photo (समाचार प्रमुख फोटो)')
                                                ->image()
                                                ->disk('public')
                                                ->directory('team')
                                                ->imageEditor()
                                                ->imageCropAspectRatio('3.5:4.5')
                                                ->maxSize(5120)
                                                ->helperText('Passport size 3.5:4.5'),

                                            FileUpload::make('bureau_chief_photo')
                                                ->label('Bureau Chief Photo (ब्युरो प्रमुख फोटो)')
                                                ->image()
                                                ->disk('public')
                                                ->directory('team')
                                                ->imageEditor()
                                                ->imageCropAspectRatio('3.5:4.5')
                                                ->maxSize(5120)
                                                ->helperText('Passport size 3.5:4.5'),
                                        ]),

                                        TextInput::make('legal_advisor')
                                            ->label('Legal Advisor (कानुनी सल्लाहकार)')
                                            ->placeholder('कानुनी सल्लाहकारको नाम')
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Custom Team Members & Staff (थप टिम सदस्यहरू)')
                                    ->description('Add any remaining team members, correspondents, columnists, or staff.')
                                    ->collapsible()
                                    ->schema([
                                        Repeater::make('additional_team_members')
                                            ->label('Additional Team Members (थप सदस्यहरू)')
                                            ->addActionLabel('+ नयाँ सदस्य थप्नुहोस् (Add Team Member)')
                                            ->collapsible()
                                            ->cloneable()
                                            ->reorderable()
                                            ->default([])
                                            ->schema([
                                                Grid::make(4)->schema([
                                                    TextInput::make('role')
                                                        ->label('Designation / Role (पद)')
                                                        ->required()
                                                        ->placeholder('उदा: वरिष्ठ संवाददाता / स्तम्भकार'),

                                                    TextInput::make('name')
                                                        ->label('Full Name (नाम)')
                                                        ->required()
                                                        ->placeholder('उदा: राम बहादुर श्रेष्ठ'),

                                                    FileUpload::make('photo')
                                                        ->label('Photo (फोटो अपलोड)')
                                                        ->image()
                                                        ->disk('public')
                                                        ->directory('team')
                                                        ->maxSize(5120),

                                                    TextInput::make('phone')
                                                        ->label('Contact Phone (सम्पर्क नम्बर)')
                                                        ->placeholder('९८XXXXXXXX'),
                                                ]),
                                            ])
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tab::make('Video')
                            ->label('Video & YouTube API')
                            ->icon('heroicon-o-video-camera')
                            ->schema([
                                Section::make('Video Section Visibility (भिडियो सेक्सन नियन्त्रण)')
                                    ->description('Control whether the video section is shown on the homepage.')
                                    ->schema([
                                        Toggle::make('show_video_section')
                                            ->label('Show Video Section on Homepage (गृहपृष्ठमा भिडियो सेक्सन देखाउने)')
                                            ->default(true)
                                            ->helperText('Enable to display the "भिडियो विशेष" section on the homepage, or disable to hide it completely.'),

                                        TextInput::make('video_section_title')
                                            ->label('Video Section Title (भिडियो सेक्सन शीर्षक)')
                                            ->default('भिडियो विशेष')
                                            ->placeholder('भिडियो विशेष'),
                                    ]),

                                Section::make('YouTube API Integration (युट्युब एपीआई इन्टिग्रेशन)')
                                    ->description('Configure YouTube Data API v3 to automatically fetch and display live YouTube videos. If API is not provided or disabled, default video articles from the database are displayed.')
                                    ->schema([
                                        Toggle::make('youtube_api_enabled')
                                            ->label('Enable YouTube API (युट्युब एपीआई सक्रिय गर्नुहोस्)')
                                            ->default(false)
                                            ->helperText('When enabled and an API key is provided, YouTube videos will be displayed. If disabled or empty, default database video articles will be shown.'),

                                        Grid::make(2)->schema([
                                            TextInput::make('youtube_api_key')
                                                ->label('YouTube Data API Key (v3)')
                                                ->password()
                                                ->revealable()
                                                ->placeholder('AIzaSy...')
                                                ->helperText('API Key from Google Cloud Console with YouTube Data API v3 enabled.'),

                                            TextInput::make('youtube_channel_id')
                                                ->label('YouTube Channel ID / Handle')
                                                ->placeholder('UC... or @KapilvastuDarpan')
                                                ->helperText('Your official channel ID or handle to fetch the latest videos.'),
                                        ]),

                                        Grid::make(2)->schema([
                                            TextInput::make('youtube_playlist_id')
                                                ->label('YouTube Playlist ID (Optional)')
                                                ->placeholder('PL...')
                                                ->helperText('Optional: Fetch videos from a specific playlist (e.g. PLxxxxxxxx).'),

                                            TextInput::make('youtube_featured_video_url')
                                                ->label('Featured Video URL or ID (वैकल्पिक विशेष भिडियो)')
                                                ->placeholder('https://www.youtube.com/watch?v=... or Video ID')
                                                ->helperText('Specific video to highlight on the left large card.'),
                                        ]),

                                        TextInput::make('youtube_cache_minutes')
                                            ->label('Cache Duration (Minutes) / क्यास समय')
                                            ->numeric()
                                            ->default(60)
                                            ->helperText('Number of minutes to cache API results to preserve your daily YouTube API quota.'),
                                    ]),
                            ]),

                        Tab::make('Rashifal')
                            ->label('Rashifal (राशिफल)')
                            ->icon('heroicon-o-sparkles')
                            ->schema([
                                Section::make('Rashifal Section Visibility (राशिफल सेक्सन नियन्त्रण)')
                                    ->description('Control whether the 12 Zodiacs horoscope section is displayed on the homepage.')
                                    ->schema([
                                        Toggle::make('show_rashifal_section')
                                            ->label('Show Rashifal Section on Homepage (गृहपृष्ठमा राशिफल सेक्सन देखाउने)')
                                            ->default(true)
                                            ->helperText('Enable to display the horoscope section on the homepage, or disable to hide it completely.'),

                                        Grid::make(2)->schema([
                                            TextInput::make('rashifal_section_title')
                                                ->label('Section Title (राशिफल सेक्सन शीर्षक)')
                                                ->default('दैनिक राशिफल')
                                                ->placeholder('दैनिक राशिफल'),

                                            TextInput::make('rashifal_section_subtitle')
                                                ->label('Section Subtitle (राशिफल सेक्सन उप-शीर्षक)')
                                                ->default('आजको ग्रहगोचर तथा १२ राशिको विस्तृत फलादेश')
                                                ->placeholder('आजको ग्रहगोचर तथा १२ राशिको विस्तृत फलादेश'),
                                        ]),
                                    ]),
                            ]),

                        Tab::make('Social')
                            ->label('Social Links')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Section::make('Social Media Presence')
                                    ->description('Official social handles displayed across header, footer and shares.')
                                    ->schema([
                                         Grid::make(3)->schema([
                                            TextInput::make('social_facebook')
                                                ->label('Facebook URL')
                                                ->url()
                                                ->placeholder('https://facebook.com/...'),

                                            TextInput::make('social_twitter')
                                                ->label('Twitter / X URL')
                                                ->url()
                                                ->placeholder('https://x.com/...'),

                                            TextInput::make('social_instagram')
                                                ->label('Instagram URL')
                                                ->url()
                                                ->placeholder('https://instagram.com/...'),
                                        ]),

                                        Grid::make(3)->schema([
                                            TextInput::make('social_youtube')
                                                ->label('YouTube URL')
                                                ->url()
                                                ->placeholder('https://youtube.com/...'),

                                            TextInput::make('social_tiktok')
                                                ->label('TikTok URL')
                                                ->url()
                                                ->placeholder('https://tiktok.com/@...'),

                                            TextInput::make('social_whatsapp')
                                                ->label('WhatsApp Number or URL')
                                                ->placeholder('+९७७-९७६६२५०४४५'),
                                        ]),
                                    ]),
                            ]),

                        Tab::make('SEO')
                            ->label('SEO & System')
                            ->icon('heroicon-o-code-bracket')
                            ->schema([
                                Section::make('Search Engine Optimization')
                                    ->description('Default meta information and custom tracking/header embed codes.')
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->label('Default SEO Meta Title')
                                            ->placeholder('कपिलवस्तु दर्पण | सत्य, तथ्य र निष्पक्ष पत्रकारिता')
                                            ->helperText('Primary portal search engine title / homepage title.')
                                            ->columnSpanFull(),

                                        Textarea::make('meta_description')
                                            ->label('Default Meta Description')
                                            ->rows(3)
                                            ->columnSpanFull(),

                                        Textarea::make('meta_keywords')
                                            ->label('Default Meta Keywords')
                                            ->rows(2)
                                            ->columnSpanFull(),

                                        Textarea::make('header_scripts')
                                            ->label('Custom Header Scripts (Google Analytics, AdSense, etc.)')
                                            ->rows(4)
                                            ->placeholder('<script async src="..."></script>')
                                            ->helperText('Injected inside <head> on all public frontend pages.')
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('User Authentication & Security (प्रयोगकर्ता तथा सुरक्षा व्यवस्था)')
                                    ->description('Frontend account registration, login policies, and email verification settings.')
                                    ->schema([
                                        Toggle::make('enable_email_verification')
                                            ->label('Require Email Verification for Signup/Signin (इमेल प्रमाणीकरण अनिवार्य गर्ने)')
                                            ->helperText('यदि सक्रिय (enabled) गरेमा नयाँ प्रयोगकर्ता दर्ता गर्दा र लगइन गर्दा इमेल प्रमाणीकरण अनिवार्य हुनेछ। बन्द (disabled) हुँदा प्रयोगकर्ता बिना झन्झट तुरुन्त दर्ता र लगइन हुन सक्छन् (पूर्वनिर्धारित: बन्द)।')
                                            ->default(false),
                                    ]),

                                Section::make('Browser Push Notifications (ब्राउजर सूचना सदस्यता विजेट)')
                                    ->description('पाठकहरूलाई ताजा तथा ब्रेकिङ समाचारहरूको सूचना दिन स्क्रिनको बायाँ-तल (Left Bottom) कुनामा नोटिफिकेसन सदस्यता विजेट र पपअप नियन्त्रण गर्नुहोस्।')
                                    ->schema([
                                        Toggle::make('push_notifications_enabled')
                                            ->label('Enable Notification Subscriber Widget (सूचना सदस्यता विजेट सक्रिय गर्ने)')
                                            ->helperText('यदि सक्रिय गरेमा वेबसाइटको बायाँ-तल (Left Bottom) घन्टी (Bell) विजेट र सदस्यता पपअप देखा पर्नेछ।')
                                            ->default(true),
                                    ]),
                            ]),

                        Tab::make('ShareMarket')
                            ->label('शेयर बजार (NEPSE)')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                Section::make('NEPSE Live Market Display Settings (शेयर बजार फिचर)')
                                    ->description('कपिलवस्तु दर्पण पोर्टलमा नेपाल स्टक एक्सचेन्ज (NEPSE) लाइभ शेयर बजार फिचर तथा हेडर टिकर नियन्त्रण गर्नुहोस्।')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Toggle::make('sharemarket_enabled')
                                                ->label('Enable Share Market Feature (शेयर बजार सक्रिय गर्ने)')
                                                ->helperText('यदि सक्रिय गरेमा वेबसाइटमा शेयर बजार पृष्ठ (/sharemarket) र मेनु लिंकहरू खुला हुनेछन्।')
                                                ->default(true),

                                            Toggle::make('sharemarket_ticker_enabled')
                                                ->label('Show Header Live Ticker (हेडरमा लाइभ टिकर देखाउने)')
                                                ->helperText('वेबसाइटको हेडर मुनि नेप्से परिसूचकको प्रत्यक्ष स्वचालित स्क्रोलिङ टिकर पट्टी देखाउने।')
                                                ->default(true),
                                        ]),
                                    ]),

                                Section::make('ShareHub Nepal API Credentials (एपीआई सेटिङ्स)')
                                    ->description('ShareHub Nepal NEPSE Data API मार्फत लाइभ नेप्से डेटा ल्याउन आफ्नो खाता विवरण प्रविष्ट गर्नुहोस् (Documentation: https://sharehubnepal.com/data/nepse-data-documentation/)। खाता नभएको अवस्थामा पनि पोर्टलले स्वचालित रूपमा भरपर्दो वास्तविक बजार विवरण प्रस्तुत गर्दछ।')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            TextInput::make('sharehub_api_email')
                                                ->label('ShareHub Account Email')
                                                ->email()
                                                ->placeholder('user@example.com')
                                                ->helperText('ShareHub Nepal मा दर्ता आधिकारिक इमेल'),

                                            TextInput::make('sharehub_api_password')
                                                ->label('ShareHub Account Password')
                                                ->password()
                                                ->revealable()
                                                ->placeholder('••••••••')
                                                ->helperText('ShareHub Nepal खाताको पासवर्ड'),

                                            TextInput::make('sharehub_cache_ttl')
                                                ->label('API Cache TTL (Seconds)')
                                                ->numeric()
                                                ->default(60)
                                                ->placeholder('60')
                                                ->helperText('डेटा क्यास रहने अवधि (सेकेन्डमा, सिफारिस: ६० सेकेन्ड)'),
                                        ]),
                                    ]),
                            ]),

                        Tab::make('Backup')
                            ->label('डाटाबेस ब्याकअप (Backup)')
                            ->icon('heroicon-o-arrow-down-tray')
                            ->visible(fn (): bool => auth()->user()?->isSuperAdmin() ?? false)
                            ->schema([
                                Section::make('Database SQL Export & Backup (डाटाबेस ब्याकअप तथा डाउनलोड)')
                                    ->description('सम्पूर्ण वेबसाइटको डाटाबेस SQL ढाँचामा (database.sql) ब्याकअप डाउनलोड गर्नुहोस्। यो ब्याकअप फाइल स्थानीय वा अन्य सर्भरमा तुरुन्तै पुनर्स्थापना (Restore/Import) गर्न सकिन्छ।')
                                    ->schema([
                                        \Filament\Forms\Components\Placeholder::make('database_stats')
                                            ->label('Current Database Overview (हालको डाटाबेस स्थिति)')
                                            ->content(function () {
                                                $stats = app(\App\Services\DatabaseBackupService::class)->getDatabaseStats();
                                                return new \Illuminate\Support\HtmlString('
                                                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1.25rem; font-size: 0.875rem;">
                                                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.25rem;">
                                                            <div style="padding: 0.75rem; background: #ffffff; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                                                                <div style="color: #64748b; font-size: 0.75rem; font-weight: 600;">DATABASE ENGINE</div>
                                                                <div style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin-top: 2px;">' . e($stats['driver']) . ' (' . e($stats['database']) . ')</div>
                                                            </div>
                                                            <div style="padding: 0.75rem; background: #ffffff; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                                                                <div style="color: #64748b; font-size: 0.75rem; font-weight: 600;">TOTAL FILE SIZE</div>
                                                                <div style="font-size: 1.15rem; font-weight: 700; color: #059669; margin-top: 2px;">' . e($stats['formatted_size']) . '</div>
                                                            </div>
                                                            <div style="padding: 0.75rem; background: #ffffff; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                                                                <div style="color: #64748b; font-size: 0.75rem; font-weight: 600;">DATABASE TABLES</div>
                                                                <div style="font-size: 1.15rem; font-weight: 700; color: #0284c7; margin-top: 2px;">' . e($stats['tables_count']) . ' Tables</div>
                                                            </div>
                                                            <div style="padding: 0.75rem; background: #ffffff; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                                                                <div style="color: #64748b; font-size: 0.75rem; font-weight: 600;">PUBLISHED ARTICLES</div>
                                                                <div style="font-size: 1.15rem; font-weight: 700; color: #dc2626; margin-top: 2px;">' . e($stats['articles_count']) . ' Articles</div>
                                                            </div>
                                                        </div>

                                                        <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center;">
                                                            <a href="' . route('admin.database.export') . '" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #059669; color: #ffffff; padding: 0.65rem 1.25rem; border-radius: 0.5rem; font-weight: 700; text-decoration: none; box-shadow: 0 2px 4px rgba(5, 150, 105, 0.2);">
                                                                <span>💾 Download database.sql</span>
                                                            </a>
                                                            <a href="' . route('admin.database.export', ['filename' => 'timestamped']) . '" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #ffffff; color: #334155; padding: 0.65rem 1.25rem; border-radius: 0.5rem; font-weight: 600; text-decoration: none; border: 1px solid #cbd5e1;">
                                                                <span>📅 Download Timestamped .sql</span>
                                                            </a>
                                                        </div>
                                                        <p style="margin-top: 0.75rem; color: #64748b; font-size: 0.8rem; margin-bottom: 0;">
                                                            💡 <strong>सिफारिस:</strong> साइटमा ठूला परिवर्तन गर्नु अघि वा हप्ताको एक पटक डाटाबेसको ब्याकअप सुरक्षित राख्नुहोला।
                                                        </p>
                                                    </div>
                                                ');
                                            }),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFormContentComponent(),
            ]);
    }

    public function getFormContentComponent(): Component
    {
        return Form::make([EmbeddedSchema::make('form')])
            ->id('form')
            ->livewireSubmitHandler('save')
            ->footer([
                Actions::make($this->getFormActions()),
            ]);
    }

    /**
     * @return array<Action>
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->submit('save')
                ->color('primary')
                ->icon('heroicon-o-check'),
        ];
    }

    public function save(): void
    {
        $state = $this->form->getState();

        Setting::setMany($state);

        Cache::flush();
        try {
            \Illuminate\Support\Facades\Artisan::call('view:clear');
        } catch (\Throwable $e) {}

        Notification::make()
            ->title('Settings Saved')
            ->body('Website settings have been updated successfully.')
            ->success()
            ->send();
    }
}
