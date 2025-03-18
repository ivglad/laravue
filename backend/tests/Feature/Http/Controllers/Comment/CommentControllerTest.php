<?php

namespace Http\Controllers\Comment;

use App\Enums\CommentModel;
use App\Models\Comment\Comment;
use Random\RandomException;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    public function testUpdate()
    {
        $commentId = Comment::factory()->create(['user_id' => $this->user->id])->id;
        $response = $this->put(route('comment.update', $commentId), [
            'content' => 'I am a warning',
            'action' => 'warning',
        ], ['Authorization' => $this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'created_at',
                'updated_at',
                'content',
                'user',
                'action',
            ],
        ]);
        $response->assertJsonPath('data.content', 'I am a warning');
        $response->assertJsonPath('data.action', 'warning');
        $comment = Comment::find($commentId);
        $this->assertEquals('I am a warning', $comment->content);
        $this->assertEquals('warning', $comment->action);
        $this->assertEquals($this->user->id, $comment->user_id);
    }

    /**
     * @throws RandomException
     */
    public function testDestroy()
    {
        $count = random_int(5, 10);
        foreach (range(1, $count) as $i) {
            $ids[] = Comment::factory()->create(['user_id' => $this->user->id])->id;
        }
        $response = $this->delete(route('comment.destroy'), [
            'ids' => $ids,
        ], ['Authorization' => $this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
        ]);
        $response->assertJsonPath('message', 'Успешно удалено');
    }

    public function testStore()
    {
        $response = $this->post(route('comment.store'), [
            'commentable_type' => CommentModel::User->value,
            'commentable_id' => $this->user->id,
            'content' => 'I am a comment',
            'action' => 'default',
        ], ['Authorization' => $this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'created_at',
                'updated_at',
                'content',
                'user',
                'action',
            ],
        ]);
        $response->assertJsonPath('data.content', 'I am a comment');
        $response->assertJsonPath('data.action', 'default');
        $commentId = $response->json('data.id');
        $comment = Comment::find($commentId);
        $this->assertEquals('I am a comment', $comment->content);
        $this->assertEquals('default', $comment->action);
        $this->assertEquals($this->user->id, $comment->user_id);
    }
}
