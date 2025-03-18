<?php

namespace Http\Controllers\File;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class FileControllerTest extends TestCase
{
    public function testStoreAndDestroy()
    {
        $file = UploadedFile::fake()->image('avatar.jpg');
        $response = $this->post(route('file.store'), [
            'model_type' => 'user',
            'model_id' => $this->user->id,
            'collection' => 'default',
            'files' => [
                $file,
            ],
        ], ['Content-Type' => 'multipart/form-data', 'Authorization' => $this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'created_at',
                    'size',
                    'file_name',
                    'mime_type',
                    'collection',
                    'sort',
                ],
            ]
        ]);
        $this->assertDatabaseHas('media', [
            'collection_name' => 'default',
            'file_name' => 'avatar.jpg',
        ]);

        $response = $this->delete(route('file.destroy', [
            'ids' => [$response->json('data.0.id')],
        ]), ['Authorization' => $this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['message']);
        $response->assertJson(['message' => 'Успешно удалено']);
        $this->assertDatabaseMissing('media', [
            'collection_name' => 'default',
            'file_name' => 'avatar.jpg',
        ]);
    }

    public function testReorder()
    {
        $files[] = UploadedFile::fake()->image('avatar1.jpg');
        $files[] = UploadedFile::fake()->image('avatar2.jpg');
        $files[] = UploadedFile::fake()->image('avatar3.jpg');
        $response = $this->post(route('file.store'), [
            'model_type' => 'user',
            'model_id' => $this->user->id,
            'collection' => 'default',
            'files' => $files,
        ], ['Content-Type' => 'multipart/form-data', 'Authorization' => $this->token]);

        $response = $this->patch(route('file.reorder'), [
            $response->json('data.2.id'),
            $response->json('data.0.id'),
            $response->json('data.1.id'),
        ], ['Authorization' => $this->token]);
        $response->assertStatus(200);
        $response->assertJson([]);
        $this->assertDatabaseHas('media', [
            'collection_name' => 'default',
            'file_name' => 'avatar3.jpg',
            'order_column' => 1,
        ]);
        $this->assertDatabaseHas('media', [
            'collection_name' => 'default',
            'file_name' => 'avatar1.jpg',
            'order_column' => 2,
        ]);
        $this->assertDatabaseHas('media', [
            'collection_name' => 'default',
            'file_name' => 'avatar2.jpg',
            'order_column' => 3,
        ]);
    }
}
