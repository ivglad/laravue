<?php

namespace Http\Controllers\Activity;

use Tests\TestCase;

class ActivityControllerTest extends TestCase
{
    public function testHandbook()
    {
        $response = $this->get(route('activity.handbook'), ['Authorization' => $this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'events',
            'subject_types',
        ]);
    }
}
