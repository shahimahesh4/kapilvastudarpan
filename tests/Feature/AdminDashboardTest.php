<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Filament\Auth\Pages\Login;
use Livewire\Livewire;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    protected function getAdminUser(): User
    {
        return User::where('role', User::ROLE_SUPER_ADMIN)->first()
            ?? User::where('role', User::ROLE_ADMIN)->first()
            ?? User::first();
    }

    public function test_admin_can_login_via_filament_login_page(): void
    {
        $admin = User::where('email', 'administrator@kapilvastudarpan.com')->first() ?? $this->getAdminUser();
        $admin->update(['password' => bcrypt('password')]);

        Livewire::test(Login::class)
            ->fillForm([
                'email' => $admin->email,
                'password' => 'password',
            ])
            ->call('authenticate')
            ->assertHasNoFormErrors()
            ->assertRedirect('/stnapanel');

        $this->assertAuthenticated();
    }

    public function test_admin_dashboard_and_pages_render_without_errors(): void
    {
        $user = $this->getAdminUser();
        $this->assertNotNull($user, 'Admin user should exist');

        $this->actingAs($user);

        // Dashboard
        $dashboardResponse = $this->get('/stnapanel');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('कपिलवस्तु दर्पण');
        $dashboardResponse->assertSee('fi-topbar-custom-actions', false);
        $dashboardResponse->assertSee('fi-topbar-date-chip', false);
        $dashboardResponse->assertSee('fi-topbar-website-btn', false);
        $dashboardResponse->assertSee('वेबसाइट हेर्नुहोस्');

        \Livewire\Livewire::test(\App\Filament\Widgets\DashboardWelcomeWidget::class)->assertSuccessful();
        \Livewire\Livewire::test(\App\Filament\Widgets\NewsStatsOverviewWidget::class)->assertSuccessful();
        \Livewire\Livewire::test(\App\Filament\Widgets\ArticlesChartWidget::class)->assertSuccessful();
        \Livewire\Livewire::test(\App\Filament\Widgets\CategoryDistributionChartWidget::class)->assertSuccessful();
        \Livewire\Livewire::test(\App\Filament\Widgets\RecentArticlesTableWidget::class)->assertSuccessful();
        \Livewire\Livewire::test(\App\Filament\Widgets\RecentCommentsTableWidget::class)->assertSuccessful();
        \Livewire\Livewire::test(\App\Filament\Widgets\PortalQuickOptionsWidget::class)
            ->assertSuccessful()
            ->call('clearSystemCache')
            ->call('clearViewCache');
        \Livewire\Livewire::test(\Filament\Pages\Dashboard::class)->assertSuccessful();

        // Articles List
        $articlesResponse = $this->get('/stnapanel/articles');
        $articlesResponse->assertStatus(200);
        $articlesResponse->assertSee('Total Articles');
        $articlesResponse->assertSee('Published');
        $articlesResponse->assertSee('Unpublish');
        $articlesResponse->assertSee('Trash');

        $articlesLivewire = \Livewire\Livewire::test(\App\Filament\Resources\Articles\Pages\ListArticles::class);
        $this->assertEquals(30, $articlesLivewire->instance()->getTable()->getDefaultPaginationPageOption());

        // Article Edit Form
        $article = Article::first();
        $this->assertNotNull($article);
        $editArticleResponse = $this->get("/stnapanel/articles/{$article->id}/edit");
        $editArticleResponse->assertStatus(200);

        // Article Create Form
        $createArticleResponse = $this->get('/stnapanel/articles/create');
        $createArticleResponse->assertStatus(200);

        // Categories List
        $categoriesResponse = $this->get('/stnapanel/categories');
        $categoriesResponse->assertStatus(200);

        // Category Edit Form
        $category = Category::first();
        $this->assertNotNull($category);
        $editCategoryResponse = $this->get("/stnapanel/categories/{$category->id}/edit");
        $editCategoryResponse->assertStatus(200);

        // Advertisements List
        $adsResponse = $this->get('/stnapanel/advertisements');
        $adsResponse->assertStatus(200);

        // Advertisement Create Form
        $createAdResponse = $this->get('/stnapanel/advertisements/create');
        $createAdResponse->assertStatus(200);

        // Menu Items List (WordPress-Style Menu Manager)
        $menuItemsResponse = $this->get('/stnapanel/menu-items');
        $menuItemsResponse->assertStatus(200);
        $menuItemsResponse->assertSee('Menus');

        // Menu Item Create Form
        $createMenuResponse = $this->get('/stnapanel/menu-items/create');
        $createMenuResponse->assertStatus(200);

        // Menu Item Edit Form
        $menuItem = \App\Models\MenuItem::first();
        if ($menuItem) {
            $editMenuResponse = $this->get("/stnapanel/menu-items/{$menuItem->id}/edit");
            $editMenuResponse->assertStatus(200);
        }
    }

    public function test_article_supports_multiple_categories_and_appears_in_all_assigned_archives(): void
    {
        $cat1 = Category::first();
        $cat2 = Category::skip(1)->first();
        $this->assertNotNull($cat1);
        $this->assertNotNull($cat2);

        $article = Article::create([
            'category_id' => $cat1->id,
            'title' => 'परीक्षण बहु-श्रेणी समाचार',
            'slug' => 'test-multi-category-' . time(),
            'body' => '<p>यो बहु-श्रेणी परीक्षण समाचार हो।</p>',
            'excerpt' => 'परीक्षण संक्षेप',
            'author' => 'परीक्षक',
            'published_at' => now(),
            'views' => 10,
        ]);

        $article->categories()->sync([$cat1->id, $cat2->id]);
        $article->save();

        $this->assertCount(2, $article->fresh()->categories);
        $this->assertTrue($cat1->articles()->where('articles.id', $article->id)->exists());
        $this->assertTrue($cat2->articles()->where('articles.id', $article->id)->exists());

        // Test category archive responses
        $res1 = $this->get(route('category.show', $cat1->slug));
        $res1->assertStatus(200);
        $res1->assertSee($article->title);

        $res2 = $this->get(route('category.show', $cat2->slug));
        $res2->assertStatus(200);
        $res2->assertSee($article->title);

        // Clean up created test article
        $article->categories()->detach();
        $article->forceDelete();
    }

    public function test_admin_can_create_article_with_multiple_categories_via_filament(): void
    {
        $user = $this->getAdminUser();
        $this->actingAs($user);

        $cat1 = Category::first();
        $cat2 = Category::skip(1)->first();

        Livewire::test(\App\Filament\Resources\Articles\Pages\CreateArticle::class)
            ->fillForm([
                'title' => 'Filament बहु-श्रेणी टेस्ट',
                'slug' => 'filament-multi-cat-' . time(),
                'body' => '<p>सामग्री</p>',
                'categories' => [$cat1->id, $cat2->id],
                'author' => 'Admin Reporter',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $created = Article::where('title', 'Filament बहु-श्रेणी टेस्ट')->first();
        $this->assertNotNull($created);
        $this->assertCount(2, $created->categories);
        $this->assertEquals($cat1->id, $created->category_id);

        $created->categories()->detach();
        $created->forceDelete();
    }

    public function test_article_publish_and_unpublish_functionality(): void
    {
        $category = Category::first();
        $article = Article::create([
            'category_id' => $category->id,
            'title' => 'प्रकाशन स्थिति परीक्षण समाचार',
            'slug' => 'publish-test-' . time(),
            'body' => '<p>परीक्षण सामग्री</p>',
            'excerpt' => 'संक्षिप्त',
            'author' => 'सम्पादक',
            'is_published' => true,
            'status' => 'published',
            'published_at' => now()->subMinute(),
        ]);
        $article->categories()->syncWithoutDetaching([$category->id]);

        try {
            // Verify published state
            $this->assertTrue($article->is_published);
            $this->assertEquals('published', $article->status);
            $this->assertTrue(Article::published()->where('id', $article->id)->exists());

            $resPublished = $this->get(route('articles.show', $article->slug));
            $resPublished->assertStatus(200);

            // Unpublish the article
            $article->unpublish();
            $this->assertFalse($article->fresh()->is_published);
            $this->assertEquals('draft', $article->fresh()->status);
            $this->assertFalse(Article::published()->where('id', $article->id)->exists());

            // Unpublished article returns 404 to public readers
            $resUnpublished = $this->get(route('articles.show', $article->slug));
            $resUnpublished->assertStatus(404);

            // Re-publish the article
            $article->publish();
            $this->assertTrue($article->fresh()->is_published);
            $this->assertEquals('published', $article->fresh()->status);
            $this->assertTrue(Article::published()->where('id', $article->id)->exists());

            $resRePublished = $this->get(route('articles.show', $article->slug));
            $resRePublished->assertStatus(200);
        } finally {
            $article->categories()->detach();
            $article->forceDelete();
        }
    }

    public function test_article_deletion_moves_to_trash_and_can_be_permanently_deleted(): void
    {
        $category = Category::first();
        $article = Article::create([
            'category_id' => $category->id,
            'title' => 'ट्र्यास परीक्षण समाचार',
            'slug' => 'trash-test-' . time(),
            'body' => '<p>यो ट्र्यास परीक्षण समाचार हो।</p>',
            'excerpt' => 'संक्षिप्त',
            'author' => 'सम्पादक',
            'is_published' => true,
            'published_at' => now(),
        ]);
        $article->categories()->syncWithoutDetaching([$category->id]);
        $articleId = $article->id;

        // 1. Delete moves to trash (soft delete)
        $article->delete();

        $this->assertTrue($article->trashed());
        $this->assertSoftDeleted('articles', ['id' => $articleId]);
        $this->assertFalse(Article::where('id', $articleId)->exists());
        $this->assertTrue(Article::onlyTrashed()->where('id', $articleId)->exists());

        // Trashed article returns 404
        $resTrashed = $this->get(route('articles.show', $article->slug));
        $resTrashed->assertStatus(404);

        // 2. Restore from trash
        $article->restore();
        $this->assertFalse($article->fresh()->trashed());
        $this->assertTrue(Article::where('id', $articleId)->exists());
        $this->assertFalse(Article::onlyTrashed()->where('id', $articleId)->exists());

        // 3. Move to trash again and permanently delete (force delete)
        $article->delete();
        $this->assertTrue($article->fresh()->trashed());

        $article->forceDelete();
        $this->assertDatabaseMissing('articles', ['id' => $articleId]);
        $this->assertFalse(Article::withTrashed()->where('id', $articleId)->exists());
    }

    public function test_trash_tab_displays_trashed_articles(): void
    {
        $user = $this->getAdminUser();
        $this->actingAs($user);

        $category = Category::first();
        $article = Article::create([
            'category_id' => $category->id,
            'title' => 'परीक्षण ट्र्यास समाचार ' . time(),
            'slug' => 'test-trash-tab-' . time(),
            'body' => '<p>परीक्षण सामग्री</p>',
            'excerpt' => 'संक्षिप्त',
            'author' => 'सम्पादक',
            'is_published' => true,
            'published_at' => now(),
        ]);
        $article->categories()->syncWithoutDetaching([$category->id]);
        $article->delete();

        \Livewire\Livewire::test(\App\Filament\Resources\Articles\Pages\ListArticles::class)
            ->set('activeTab', 'trash')
            ->assertSee($article->title)
            ->assertTableActionVisible('restore', $article)
            ->assertTableActionVisible('forceDelete', $article)
            ->assertTableActionHidden('delete', $article)
            ->assertTableActionHidden('edit', $article);

        \Livewire\Livewire::test(\App\Filament\Resources\Articles\Pages\ListArticles::class)
            ->set('activeTab', 'total')
            ->assertDontSee($article->title);

        $article->categories()->detach();
        $article->forceDelete();
    }

    public function test_admin_can_access_website_settings_page(): void
    {
        $user = $this->getAdminUser();
        $this->actingAs($user);

        $response = $this->get('/stnapanel/website-settings');
        $response->assertStatus(200);
        $response->assertSee('Website Settings');
    }

    public function test_admin_can_update_website_settings(): void
    {
        $user = $this->getAdminUser();
        $this->actingAs($user);

        try {
            \Livewire\Livewire::test(\App\Filament\Pages\WebsiteSettings::class)
                ->fillForm([
                    'site_name' => 'कपिलवस्तु दर्पण नयाँ',
                    'contact_email' => 'new-contact@kapilvastudarpan.com',
                    'contact_phone' => '+९७७-९८००००००००',
                    'website_url' => 'https://kapilvastudarpan.com',
                    'contact_address' => 'नयाँ ठेगाना, कपिलवस्तु',
                ])
                ->call('save')
                ->assertHasNoFormErrors();

            $this->assertEquals('new-contact@kapilvastudarpan.com', \App\Models\Setting::get('contact_email'));
            $this->assertEquals('+९७७-९८००००००००', \App\Models\Setting::get('contact_phone'));
            $this->assertEquals('https://kapilvastudarpan.com', \App\Models\Setting::get('website_url'));
            $this->assertEquals('कपिलवस्तु दर्पण नयाँ', setting('site_name'));

            // Verify frontend displays updated contact info
            $response = $this->get(route('home'));
            $response->assertStatus(200);
            $response->assertSee('new-contact@kapilvastudarpan.com');
            $response->assertSee('+९७७-९८००००००००');
            $response->assertSee('kapilvastudarpan.com');
        } finally {
            // Restore official website settings so database maintains production data
            \App\Models\Setting::set('site_name', 'कपिलवस्तु दर्पण');
            \App\Models\Setting::set('contact_email', 'kapilvastudarpannews@gmail.com');
            \App\Models\Setting::set('contact_address', 'कपिलवस्तु–१०, पचेहरा, लुम्बिनी प्रदेश, नेपाल');
            \App\Models\Setting::set('contact_phone', '+९७७-९७६६२५०४४५');
            \App\Models\Setting::set('website_url', 'https://kapilvastudarpan.com');
            \Illuminate\Support\Facades\Cache::flush();
        }
    }

    public function test_admin_can_update_video_and_youtube_settings(): void
    {
        $user = $this->getAdminUser();
        $this->actingAs($user);

        \Livewire\Livewire::test(\App\Filament\Pages\WebsiteSettings::class)
            ->fillForm([
                'show_video_section' => true,
                'video_section_title' => 'नयाँ भिडियो विशेष',
                'youtube_api_enabled' => true,
                'youtube_api_key' => 'AIzaSyDemoKey12345',
                'youtube_channel_id' => 'UC_demo_channel_id',
                'youtube_featured_video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertTrue(\App\Models\Setting::getBool('show_video_section'));
        $this->assertTrue(\App\Models\Setting::getBool('youtube_api_enabled'));
        $this->assertEquals('AIzaSyDemoKey12345', \App\Models\Setting::get('youtube_api_key'));
        $this->assertEquals('UC_demo_channel_id', \App\Models\Setting::get('youtube_channel_id'));
        $this->assertEquals('https://www.youtube.com/watch?v=dQw4w9WgXcQ', \App\Models\Setting::get('youtube_featured_video_url'));
        $this->assertEquals('नयाँ भिडियो विशेष', \App\Models\Setting::get('video_section_title'));

        // Reset settings
        \App\Models\Setting::set('youtube_api_enabled', false);
        \App\Models\Setting::set('youtube_api_key', '');
        \App\Models\Setting::set('youtube_channel_id', '');
        \App\Models\Setting::set('youtube_featured_video_url', '');
        \App\Models\Setting::set('video_section_title', 'भिडियो विशेष');
    }

    public function test_admin_can_access_rashifals_resource_and_edit_record(): void
    {
        $user = $this->getAdminUser();
        $this->actingAs($user);

        // List page
        $listResponse = $this->get('/stnapanel/rashifals');
        $listResponse->assertStatus(200);
        $listResponse->assertSee('मेष');
        $listResponse->assertSee('मीन');

        // Edit page
        $mesh = \App\Models\Rashifal::where('slug', 'mesh')->first();
        $this->assertNotNull($mesh);

        $editResponse = $this->get("/stnapanel/rashifals/{$mesh->id}/edit");
        $editResponse->assertStatus(200);
        $editResponse->assertSee('राशिको आधारभूत विवरण');
        $editResponse->assertSee('दैनिक राशिफल');
        $editResponse->assertSee('वार्षिक राशिफल');
    }

    public function test_admin_can_update_rashifal_settings_via_website_settings_page(): void
    {
        $user = $this->getAdminUser();
        $this->actingAs($user);

        \Livewire\Livewire::test(\App\Filament\Pages\WebsiteSettings::class)
            ->fillForm([
                'show_rashifal_section' => true,
                'rashifal_section_title' => 'आजको १२ राशिको फल',
                'rashifal_section_subtitle' => 'विशेष दैनिक ग्रहगोचर',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertTrue(\App\Models\Setting::getBool('show_rashifal_section'));
        $this->assertEquals('आजको १२ राशिको फल', \App\Models\Setting::get('rashifal_section_title'));
        $this->assertEquals('विशेष दैनिक ग्रहगोचर', \App\Models\Setting::get('rashifal_section_subtitle'));

        // Reset settings
        \App\Models\Setting::set('rashifal_section_title', 'दैनिक राशिफल');
        \App\Models\Setting::set('rashifal_section_subtitle', 'आजको ग्रहगोचर तथा १२ राशिको विस्तृत फलादेश');
    }

    public function test_admin_can_upload_leader_photo_via_website_settings(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $user = $this->getAdminUser();
        $this->actingAs($user);

        $fakePhoto = \Illuminate\Http\UploadedFile::fake()->image('test-operator-portrait.jpg', 350, 450);

        \Livewire\Livewire::test(\App\Filament\Pages\WebsiteSettings::class)
            ->set('data.operator_photo', $fakePhoto)
            ->call('save')
            ->assertHasNoFormErrors();

        $savedPhotoPath = \App\Models\Setting::get('operator_photo');
        $this->assertNotEmpty($savedPhotoPath);
        $this->assertStringContainsString('team/', $savedPhotoPath);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($savedPhotoPath);

        // Check helper resolution
        $resolvedUrl = setting_image_url($savedPhotoPath);
        $this->assertStringContainsString('storage/team/', $resolvedUrl);

        // Reset operator photo back to seeded image
        \App\Models\Setting::set('operator_photo', 'images/team/taranath-kewat.png');
    }

    public function test_admin_can_update_social_instagram_and_it_displays_on_header_and_footer(): void
    {
        $user = $this->getAdminUser();
        $this->actingAs($user);

        $instagramUrl = 'https://instagram.com/kapilvastu_darpan_official';

        try {
            \Livewire\Livewire::test(\App\Filament\Pages\WebsiteSettings::class)
                ->fillForm([
                    'social_instagram' => $instagramUrl,
                ])
                ->call('save')
                ->assertHasNoFormErrors();

            $this->assertEquals($instagramUrl, \App\Models\Setting::get('social_instagram'));

            // Check front page displays the Instagram link in header and footer
            $response = $this->get('/');
            $response->assertStatus(200);
            $response->assertSee($instagramUrl, false);
        } finally {
            \App\Models\Setting::set('social_instagram', 'https://instagram.com');
            \Illuminate\Support\Facades\Cache::flush();
        }
    }

    public function test_social_icon_is_hidden_when_social_link_is_not_configured(): void
    {
        $origTiktok = \App\Models\Setting::get('social_tiktok', '');

        try {
            // 1. When empty, TikTok icon should not appear
            \App\Models\Setting::set('social_tiktok', '');
            \Illuminate\Support\Facades\Cache::flush();

            $response = $this->get('/');
            $response->assertStatus(200);
            $response->assertDontSee('aria-label="TikTok"', false);

            // 2. When filled, TikTok icon should appear
            $tiktokUrl = 'https://tiktok.com/@kapilvastu_darpan_official';
            \App\Models\Setting::set('social_tiktok', $tiktokUrl);
            \Illuminate\Support\Facades\Cache::flush();

            $resWithTiktok = $this->get('/');
            $resWithTiktok->assertStatus(200);
            $resWithTiktok->assertSee('aria-label="TikTok"', false);
            $resWithTiktok->assertSee($tiktokUrl, false);
        } finally {
            \App\Models\Setting::set('social_tiktok', $origTiktok ?: 'https://tiktok.com');
            \Illuminate\Support\Facades\Cache::flush();
        }
    }

    public function test_editor_profile_box_is_hidden_by_default_on_article_page(): void
    {
        $article = \App\Models\Article::published()->first();
        $this->assertNotNull($article);

        $origSetting = \App\Models\Setting::get('show_article_editor_box', '0');

        try {
            // 1. By default or when disabled, editor box is hidden
            \App\Models\Setting::set('show_article_editor_box', false);
            \Illuminate\Support\Facades\Cache::flush();

            $response = $this->get(route('articles.show', $article->slug));
            $response->assertStatus(200);
            $response->assertDontSee('प्रधान सम्पादक / सम्पादकीय नेतृत्व');

            // 2. When enabled, editor box is displayed
            \App\Models\Setting::set('show_article_editor_box', true);
            \Illuminate\Support\Facades\Cache::flush();

            $responseEnabled = $this->get(route('articles.show', $article->slug));
            $responseEnabled->assertStatus(200);
            $responseEnabled->assertSee('प्रधान सम्पादक / सम्पादकीय नेतृत्व');
        } finally {
            \App\Models\Setting::set('show_article_editor_box', $origSetting);
            \Illuminate\Support\Facades\Cache::flush();
        }
    }

    public function test_admin_can_access_comments_resource(): void
    {
        $admin = User::first() ?? User::factory()->create();

        $response = $this->actingAs($admin)->get('/stnapanel/comments');
        $response->assertStatus(200);
        $response->assertSee('Comments');

        $article = Article::published()->first() ?? Article::factory()->create();
        $comment = \App\Models\Comment::create([
            'article_id' => $article->id,
            'user_id' => $admin->id,
            'content' => 'Filament ActionGroup परीक्षण प्रतिक्रिया',
            'is_approved' => false,
        ]);

        try {
            \Livewire\Livewire::test(\App\Filament\Resources\Comments\Pages\ListComments::class)
                ->assertTableActionExists('approve')
                ->assertTableActionExists('unapprove')
                ->assertTableActionExists('edit')
                ->assertTableActionExists('delete')
                ->assertTableActionVisible('approve', $comment)
                ->assertTableActionHidden('unapprove', $comment)
                ->callTableAction('approve', $comment);

            $this->assertTrue($comment->fresh()->is_approved);

            \Livewire\Livewire::test(\App\Filament\Resources\Comments\Pages\ListComments::class)
                ->assertTableActionVisible('unapprove', $comment->fresh())
                ->assertTableActionHidden('approve', $comment->fresh())
                ->callTableAction('unapprove', $comment->fresh());

            $this->assertFalse($comment->fresh()->is_approved);
        } finally {
            $comment->delete();
        }
    }

    protected function tearDown(): void
    {
        $testPatterns = ['test-multi-category-%', 'trash-test-%', 'test-trash-tab-%'];
        foreach ($testPatterns as $pattern) {
            $articles = Article::withTrashed()->where('slug', 'like', $pattern)->get();
            foreach ($articles as $art) {
                $art->categories()->detach();
                $art->forceDelete();
            }
        }

        parent::tearDown();
    }
}


