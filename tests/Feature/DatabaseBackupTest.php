<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\DatabaseBackupService;
use PDO;
use Tests\TestCase;

class DatabaseBackupTest extends TestCase
{
    public function test_super_admin_can_download_database_sql(): void
    {
        $superAdmin = User::where('role', User::ROLE_SUPER_ADMIN)->first();
        $this->assertNotNull($superAdmin);

        $response = $this->actingAs($superAdmin)->get('/stnapanel/database/export');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/sql; charset=utf-8');
        $this->assertStringContainsString('attachment; filename="database.sql"', $response->headers->get('Content-Disposition') ?? '');

        // Verify content contains valid SQL and table definitions
        $content = $response->streamedContent();
        $this->assertStringContainsString('-- Kapilvastu Darpan', $content);
        $this->assertStringContainsString('CREATE TABLE', $content);
        $this->assertStringContainsString('articles', $content);
        $this->assertStringContainsString('users', $content);
        $this->assertStringContainsString('INSERT INTO', $content);
    }

    public function test_admin_cannot_download_database_sql(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin)->get('/stnapanel/database/export');
        $response->assertStatus(403);

        $timestampedResponse = $this->actingAs($admin)->get('/stnapanel/database/export?filename=timestamped');
        $timestampedResponse->assertStatus(403);
    }

    public function test_super_admin_can_download_timestamped_database_sql(): void
    {
        $superAdmin = User::where('role', User::ROLE_SUPER_ADMIN)->first();
        $this->assertNotNull($superAdmin);

        $response = $this->actingAs($superAdmin)->get('/stnapanel/database/export?filename=timestamped');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/sql; charset=utf-8');
        $this->assertMatchesRegularExpression('/attachment; filename="kapilvastudarpan_database_\d{4}_\d{2}_\d{2}_\d{6}\.sql"/', $response->headers->get('Content-Disposition') ?? '');
    }

    public function test_editor_and_publisher_cannot_download_database_backup(): void
    {
        $editor = User::where('role', User::ROLE_EDITOR)->first();
        $this->assertNotNull($editor);
        $editorResponse = $this->actingAs($editor)->get('/stnapanel/database/export');
        $editorResponse->assertStatus(403);

        $publisher = User::where('role', User::ROLE_PUBLISHER)->first();
        $this->assertNotNull($publisher);
        $publisherResponse = $this->actingAs($publisher)->get('/stnapanel/database/export');
        $publisherResponse->assertStatus(403);
    }

    public function test_guest_cannot_download_database_backup(): void
    {
        $response = $this->get('/stnapanel/database/export');
        $this->assertTrue($response->isRedirection() || $response->isForbidden());
    }

    public function test_database_backup_service_generates_valid_restorable_sql(): void
    {
        $service = app(DatabaseBackupService::class);
        $stats = $service->getDatabaseStats();

        $this->assertArrayHasKey('driver', $stats);
        $this->assertArrayHasKey('formatted_size', $stats);
        $this->assertArrayHasKey('tables_count', $stats);
        $this->assertGreaterThan(0, $stats['tables_count']);
        $this->assertGreaterThan(0, $stats['articles_count']);

        ob_start();
        $service->streamSqlDump();
        $sql = ob_get_clean();

        $this->assertNotEmpty($sql);

        // Verify SQL executes successfully in clean in-memory PDO
        $tempPdo = new PDO('sqlite::memory:');
        $tempPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $tempPdo->exec($sql);

        $stmt = $tempPdo->query('SELECT COUNT(*) FROM articles');
        $restoredArticlesCount = (int) $stmt->fetchColumn();

        $this->assertEquals($stats['articles_count'], $restoredArticlesCount);
    }
}
