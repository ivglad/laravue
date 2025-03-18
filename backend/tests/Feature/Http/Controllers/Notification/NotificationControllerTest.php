<?php

namespace Http\Controllers\Notification;

use App\Models\Notification;
use App\Models\User;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    public function testIndex()
    {
        Notification::factory(50)->create();
        $response = $this->get(route('notification.index'), ['Authorization' => $this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'type',
                    'is_read',
                    'user_id',
                    'title',
                    'content',
                    'meta',
                ],
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
            'meta' => [
                'unread',
                'current_page',
                'from',
                'last_page',
                'links' => [
                    '*' => [
                        'url',
                        'label',
                        'active',
                    ],
                ],
                'path',
                'per_page',
                'to',
                'total',
            ],
        ]);
    }

    public function testRead()
    {
        $otherUser = User::factory()->create();
        Notification::factory(3)->create(['user_id' => $this->user->id, 'is_read' => false]);
        Notification::factory(3)->create(['user_id' => $otherUser->id, 'is_read' => false]);
        $response = $this->patch(route('notification.read'), [
            'ids' => [1, 2, 4, 5],
        ], ['Authorization' => $this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([]);
        $this->assertDatabaseHas('notifications', [
            'id' => 1,
            'is_read' => true,
        ]);
        $this->assertDatabaseHas('notifications', [
            'id' => 2,
            'is_read' => true,
        ]);
        $this->assertDatabaseHas('notifications', [
            'id' => 3,
            'is_read' => false,
        ]);
        $this->assertDatabaseHas('notifications', [
            'id' => 4,
            'is_read' => false,
        ]);
        $this->assertDatabaseHas('notifications', [
            'id' => 5,
            'is_read' => false,
        ]);
        $this->assertDatabaseHas('notifications', [
            'id' => 6,
            'is_read' => false,
        ]);

    }
}
