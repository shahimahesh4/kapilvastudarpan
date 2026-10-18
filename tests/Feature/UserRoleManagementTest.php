<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Tests\TestCase;

class UserRoleManagementTest extends TestCase
{
    public function test_super_admin_can_access_users_resource_and_panel(): void
    {
        $superAdmin = User::where('role', User::ROLE_SUPER_ADMIN)->first();
        $this->assertNotNull($superAdmin);

        $response = $this->actingAs($superAdmin)->get('/stnapanel/users');
        $response->assertStatus(200);
        $response->assertSee('Users');
        $response->assertSee('admin@kapilvastudarpan.com');
    }

    public function test_admin_can_access_users_resource(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin)->get('/stnapanel/users');
        $response->assertStatus(200);
        $response->assertSee('Users');
    }

    public function test_editor_cannot_access_users_or_website_settings(): void
    {
        $editor = User::where('role', User::ROLE_EDITOR)->first();
        $this->assertNotNull($editor);

        // Cannot access Users
        $usersResponse = $this->actingAs($editor)->get('/stnapanel/users');
        $usersResponse->assertStatus(403);

        // Cannot access Website Settings
        $settingsResponse = $this->actingAs($editor)->get('/stnapanel/website-settings');
        $settingsResponse->assertStatus(403);

        // Cannot access Menus
        $menusResponse = $this->actingAs($editor)->get('/stnapanel/menu-items');
        $menusResponse->assertStatus(403);

        // Can access Articles
        $articlesResponse = $this->actingAs($editor)->get('/stnapanel/articles');
        $articlesResponse->assertStatus(200);

        // Can access Categories
        $categoriesResponse = $this->actingAs($editor)->get('/stnapanel/categories');
        $categoriesResponse->assertStatus(200);
    }

    public function test_publisher_can_only_access_articles_and_sees_own_articles(): void
    {
        $publisher = User::where('role', User::ROLE_PUBLISHER)->first();
        $this->assertNotNull($publisher);

        // Cannot access Users
        $usersResponse = $this->actingAs($publisher)->get('/stnapanel/users');
        $usersResponse->assertStatus(403);

        // Cannot access Categories
        $categoriesResponse = $this->actingAs($publisher)->get('/stnapanel/categories');
        $categoriesResponse->assertStatus(403);

        // Cannot access Comments
        $commentsResponse = $this->actingAs($publisher)->get('/stnapanel/comments');
        $commentsResponse->assertStatus(403);

        // Can access Articles
        $articlesResponse = $this->actingAs($publisher)->get('/stnapanel/articles');
        $articlesResponse->assertStatus(200);
    }

    public function test_subscriber_cannot_access_admin_panel(): void
    {
        $subscriber = User::where('role', User::ROLE_SUBSCRIBER)->first();
        $this->assertNotNull($subscriber);

        $response = $this->actingAs($subscriber)->get('/stnapanel');
        $response->assertStatus(403);
    }

    public function test_role_can_be_assigned_and_updated(): void
    {
        $testUser = User::create([
            'name' => 'Role Switch Test User',
            'email' => 'roleswitch@test.com',
            'password' => bcrypt('password123'),
            'role' => User::ROLE_SUBSCRIBER,
        ]);

        $this->assertEquals(User::ROLE_SUBSCRIBER, $testUser->role);
        $this->assertFalse($testUser->canAccessPanel(\Filament\Facades\Filament::getPanel('admin')));

        // Elevate to publisher
        $testUser->update(['role' => User::ROLE_PUBLISHER]);
        $this->assertTrue($testUser->canAccessPanel(\Filament\Facades\Filament::getPanel('admin')));
        $this->assertTrue($testUser->isPublisher());

        // Elevate to editor
        $testUser->update(['role' => User::ROLE_EDITOR]);
        $this->assertTrue($testUser->isEditor());
        $this->assertFalse($testUser->isAdmin());

        // Clean up
        $testUser->delete();
    }

    public function test_super_admin_can_create_user_with_role_via_filament(): void
    {
        $superAdmin = User::where('role', User::ROLE_SUPER_ADMIN)->first();
        $this->actingAs($superAdmin);

        \Livewire\Livewire::test(\App\Filament\Resources\Users\Pages\CreateUser::class)
            ->fillForm([
                'name' => 'Newly Created Editor',
                'email' => 'neweditor@test.com',
                'role' => User::ROLE_EDITOR,
                'password' => 'Password123#Secure',
                'phone' => '+977 9800000001',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $created = User::where('email', 'neweditor@test.com')->first();
        $this->assertNotNull($created);
        $this->assertEquals(User::ROLE_EDITOR, $created->role);
        $this->assertTrue($created->isEditor());

        // Test editing the user's role
        \Livewire\Livewire::test(\App\Filament\Resources\Users\Pages\EditUser::class, [
            'record' => $created->getRouteKey(),
        ])
            ->fillForm([
                'role' => User::ROLE_PUBLISHER,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $created->refresh();
        $this->assertEquals(User::ROLE_PUBLISHER, $created->role);
        $this->assertTrue($created->isPublisher());

        // Clean up
        $created->delete();
    }

    public function test_admin_cannot_edit_super_admin_via_route(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();
        $superAdmin = User::where('role', User::ROLE_SUPER_ADMIN)->first();

        $this->assertNotNull($admin);
        $this->assertNotNull($superAdmin);

        $this->actingAs($admin);

        // Attempting to visit edit page of super admin must return 403 Forbidden
        $response = $this->get('/stnapanel/users/' . $superAdmin->id . '/edit');
        $response->assertStatus(403);
    }

    public function test_admin_cannot_edit_or_delete_super_admin_via_resource_and_policy(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();
        $superAdmin = User::where('role', User::ROLE_SUPER_ADMIN)->first();

        $this->actingAs($admin);

        // UserResource permission checks
        $this->assertFalse(\App\Filament\Resources\Users\UserResource::canEdit($superAdmin));
        $this->assertFalse(\App\Filament\Resources\Users\UserResource::canDelete($superAdmin));

        // Policy checks
        $policy = new \App\Policies\UserPolicy();
        $this->assertFalse($policy->update($admin, $superAdmin));
        $this->assertFalse($policy->delete($admin, $superAdmin));

        // Model level lifecycle guard on update
        $updateBlocked = false;
        try {
            $superAdmin->name = 'Hacked by Admin';
            $superAdmin->save();
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            $updateBlocked = $e->getStatusCode() === 403;
        }
        $this->assertTrue($updateBlocked, 'Admin update of super admin must be blocked with 403');

        // Model level lifecycle guard on delete
        $deleteBlocked = false;
        try {
            $superAdmin->delete();
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            $deleteBlocked = $e->getStatusCode() === 403;
        }
        $this->assertTrue($deleteBlocked, 'Admin deletion of super admin must be blocked with 403');
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();
        $this->actingAs($admin);

        $this->assertFalse(\App\Filament\Resources\Users\UserResource::canDelete($admin));

        $policy = new \App\Policies\UserPolicy();
        $this->assertFalse($policy->delete($admin, $admin));
    }

    public function test_super_admin_can_edit_all_users(): void
    {
        $superAdmin = User::where('role', User::ROLE_SUPER_ADMIN)->first();
        $admin = User::where('role', User::ROLE_ADMIN)->first();

        $this->actingAs($superAdmin);

        $this->assertTrue(\App\Filament\Resources\Users\UserResource::canEdit($superAdmin));
        $this->assertTrue(\App\Filament\Resources\Users\UserResource::canEdit($admin));

        $response = $this->get('/stnapanel/users/' . $superAdmin->id . '/edit');
        $response->assertStatus(200);

        $responseAdmin = $this->get('/stnapanel/users/' . $admin->id . '/edit');
        $responseAdmin->assertStatus(200);
    }

    public function test_user_articles_can_be_reassigned_before_user_deletion(): void
    {
        $superAdmin = User::where('role', User::ROLE_SUPER_ADMIN)->first();
        $this->assertNotNull($superAdmin);

        // Create a temporary author with articles
        $author = User::create([
            'name' => 'Departing Reporter',
            'email' => 'departing@test.com',
            'password' => bcrypt('password123'),
            'role' => User::ROLE_PUBLISHER,
        ]);

        $article1 = Article::create([
            'user_id' => $author->id,
            'title' => 'Article by Departing Reporter 1',
            'slug' => 'departing-article-1',
            'excerpt' => 'Short excerpt 1',
            'body' => '<p>Content</p>',
            'author' => $author->name,
            'status' => 'published',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $article2 = Article::create([
            'user_id' => $author->id,
            'title' => 'Article by Departing Reporter 2',
            'slug' => 'departing-article-2',
            'excerpt' => 'Short excerpt 2',
            'body' => '<p>Content</p>',
            'author' => $author->name,
            'status' => 'draft',
            'is_published' => false,
        ]);

        $this->assertEquals(2, $author->articles()->count());

        // Reassign articles to superAdmin and delete user
        $reassignedCount = $author->reassignArticlesTo($superAdmin);
        $this->assertEquals(2, $reassignedCount);

        $author->delete();

        // Verify the user is deleted
        $this->assertNull(User::find($author->id));

        // Verify both articles now belong to superAdmin and still exist
        $article1->refresh();
        $article2->refresh();
        $this->assertEquals($superAdmin->id, $article1->user_id);
        $this->assertEquals($superAdmin->id, $article2->user_id);

        // Clean up test articles
        $article1->forceDelete();
        $article2->forceDelete();
    }
}


