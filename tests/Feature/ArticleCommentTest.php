<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Tests\TestCase;

class ArticleCommentTest extends TestCase
{
    public function test_guest_sees_login_prompt_in_comment_section_and_cannot_comment(): void
    {
        $article = Article::published()->first();
        $this->assertNotNull($article);

        $initialComments = Comment::where('article_id', $article->id)->count();

        $response = $this->get(route('articles.show', $article->slug));
        $response->assertStatus(200);
        $response->assertSee('प्रतिक्रियाहरू');
        $response->assertSee('प्रतिक्रिया दिनका लागि कृपया पहिले लगइन गर्नुहोस्');
        $response->assertDontSee('name="content"', false);

        // Guest attempting to post comment is redirected
        $postResponse = $this->post(route('articles.comments.store', $article->slug), [
            'content' => 'यो परीक्षण प्रतिक्रिया हो।',
        ]);

        $postResponse->assertRedirect(route('articles.show', $article->slug) . '#comments');
        $this->assertEquals($initialComments, Comment::where('article_id', $article->id)->count());
    }

    public function test_authenticated_user_can_post_comment(): void
    {
        $article = Article::published()->first();
        $this->assertNotNull($article);

        $uniqueKey = uniqid();
        $user = User::factory()->create([
            'name' => 'रामशरण पाठक ' . $uniqueKey,
            'email' => 'ramsharan_' . $uniqueKey . '@example.com',
        ]);

        try {
            // Viewing as authenticated user
            $response = $this->actingAs($user)->get(route('articles.show', $article->slug));
            $response->assertStatus(200);
            $response->assertSee('रामशरण पाठक ' . $uniqueKey);
            $response->assertSee('प्रतिक्रिया पठाउनुहोस्');
            $response->assertSee('name="content"', false);

            // Posting a comment
            $commentText = 'यो अत्यन्तै जानकारीमूलक समाचार छ ' . $uniqueKey;
            $postResponse = $this->actingAs($user)->post(route('articles.comments.store', $article->slug), [
                'content' => $commentText,
            ]);

            $postResponse->assertRedirect(route('articles.show', $article->slug) . '#comments');
            $postResponse->assertSessionHas('comment_success');

            $this->assertDatabaseHas('comments', [
                'article_id' => $article->id,
                'user_id' => $user->id,
                'content' => $commentText,
                'is_approved' => true,
            ]);

            // Reload page to see the newly posted comment
            $reloadResponse = $this->get(route('articles.show', $article->slug));
            $reloadResponse->assertStatus(200);
            $reloadResponse->assertSee($commentText);
            $reloadResponse->assertSee('रामशरण पाठक ' . $uniqueKey);
        } finally {
            Comment::where('user_id', $user->id)->delete();
            $user->delete();
        }
    }

    public function test_comment_author_can_delete_their_comment(): void
    {
        $article = Article::published()->first();
        $this->assertNotNull($article);

        $u1 = uniqid();
        $u2 = uniqid();
        $user = User::factory()->create(['name' => 'सिता श्रेष्ठ ' . $u1, 'email' => 'sita_' . $u1 . '@example.com']);
        $otherUser = User::factory()->create(['name' => 'हरि शर्मा ' . $u2, 'email' => 'hari_' . $u2 . '@example.com']);

        $comment = Comment::create([
            'article_id' => $article->id,
            'user_id' => $user->id,
            'content' => 'मेटाउन मिल्ने प्रतिक्रिया ' . $u1,
            'is_approved' => true,
        ]);

        try {
            // Other user cannot delete it
            $failResponse = $this->actingAs($otherUser)->delete(route('comments.destroy', $comment->id));
            $failResponse->assertStatus(403);
            $this->assertDatabaseHas('comments', ['id' => $comment->id]);

            // Author can delete it
            $successResponse = $this->actingAs($user)->delete(route('comments.destroy', $comment->id));
            $successResponse->assertRedirect(route('articles.show', $article->slug) . '#comments');
            $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
        } finally {
            $comment->delete();
            $user->delete();
            $otherUser->delete();
        }
    }

    public function test_authenticated_user_can_reply_to_existing_comment_with_emojis_and_replies_render_threaded(): void
    {
        $article = Article::published()->first();
        $this->assertNotNull($article);

        $u1 = uniqid();
        $u2 = uniqid();
        $authorUser = User::factory()->create(['name' => 'मूल लेखक ' . $u1, 'email' => 'author_' . $u1 . '@example.com']);
        $replierUser = User::factory()->create(['name' => 'जवाफकर्ता ' . $u2, 'email' => 'replier_' . $u2 . '@example.com']);

        $rootComment = Comment::create([
            'article_id' => $article->id,
            'user_id' => $authorUser->id,
            'content' => 'यो मूल टिप्पणी हो ' . $u1,
            'is_approved' => true,
        ]);

        try {
            $replyText = 'म तपाईंको कुरामा सहमत छु! 🙏👍💯 ' . $u2;

            $postResponse = $this->actingAs($replierUser)->post(route('articles.comments.store', $article->slug), [
                'content' => $replyText,
                'parent_id' => $rootComment->id,
            ]);

            $postResponse->assertRedirect(route('articles.show', $article->slug) . '#comments');
            $postResponse->assertSessionHas('comment_success', 'तपाईंको जवाफ सफलतापूर्वक पोस्ट गरियो।');

            $this->assertDatabaseHas('comments', [
                'article_id' => $article->id,
                'user_id' => $replierUser->id,
                'parent_id' => $rootComment->id,
                'content' => $replyText,
                'is_approved' => true,
            ]);

            // Verify the thread renders on the article page
            $pageResponse = $this->get(route('articles.show', $article->slug));
            $pageResponse->assertStatus(200);
            $pageResponse->assertSee($rootComment->content);
            $pageResponse->assertSee($replyText);
            $pageResponse->assertSee('जवाफ');
            $pageResponse->assertSee('इमोजि:');
            $pageResponse->assertSee('👍');
            $pageResponse->assertSee('🙏');

            // Verify relationships on the model
            $this->assertEquals($rootComment->id, Comment::where('content', $replyText)->first()->parent->id);
            $this->assertCount(1, $rootComment->fresh()->replies);
            $this->assertEquals($replyText, $rootComment->fresh()->replies->first()->content);
        } finally {
            Comment::where('article_id', $article->id)->whereIn('user_id', [$authorUser->id, $replierUser->id])->delete();
            $authorUser->delete();
            $replierUser->delete();
        }
    }
}

