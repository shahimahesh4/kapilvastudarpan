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
    public function test_admin_can_login_via_filament_login_page(): void
    {
        Livewire::test(Login::class)
            ->fillForm([
                'email' => 'admin@kapilvastudarpan.com',
                'password' => 'password',
            ])
            ->call('authenticate')
            ->assertHasNoFormErrors()
            ->assertRedirect('/admin');

        $this->assertAuthenticated();
    }

    public function test_admin_dashboard_and_pages_render_without_errors(): void
    {
        $user = User::where('email', 'admin@kapilvastudarpan.com')->first();
        $this->assertNotNull($user, 'Admin user should exist');

        $this->actingAs($user);

        // Dashboard
        $dashboardResponse = $this->get('/admin');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('कपिलवस्तु दर्पण');

        // Articles List
        $articlesResponse = $this->get('/admin/articles');
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
        $editArticleResponse = $this->get("/admin/articles/{$article->id}/edit");
        $editArticleResponse->assertStatus(200);

        // Article Create Form
        $createArticleResponse = $this->get('/admin/articles/create');
        $createArticleResponse->assertStatus(200);

        // Categories List
        $categoriesResponse = $this->get('/admin/categories');
        $categoriesResponse->assertStatus(200);

        // Category Edit Form
        $category = Category::first();
        $this->assertNotNull($category);
        $editCategoryResponse = $this->get("/admin/categories/{$category->id}/edit");
        $editCategoryResponse->assertStatus(200);

        // Advertisements List
        $adsResponse = $this->get('/admin/advertisements');
        $adsResponse->assertStatus(200);

        // Advertisement Create Form
        $createAdResponse = $this->get('/admin/advertisements/create');
        $createAdResponse->assertStatus(200);

        // Menu Items List (WordPress-Style Menu Manager)
        $menuItemsResponse = $this->get('/admin/menu-items');
        $menuItemsResponse->assertStatus(200);
        $menuItemsResponse->assertSee('Menus');

        // Menu Item Create Form
        $createMenuResponse = $this->get('/admin/menu-items/create');
        $createMenuResponse->assertStatus(200);

        // Menu Item Edit Form
        $menuItem = \App\Models\MenuItem::first();
        if ($menuItem) {
            $editMenuResponse = $this->get("/admin/menu-items/{$menuItem->id}/edit");
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
        $user = User::where('email', 'admin@kapilvastudarpan.com')->first();
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
        $article->categories()->attach($category->id);

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

        // Cleanup
        $article->categories()->detach();
        $article->forceDelete();
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
        $article->categories()->attach($category->id);
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
        $user = User::where('email', 'admin@kapilvastudarpan.com')->first();
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
        $article->categories()->attach($category->id);
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
}
