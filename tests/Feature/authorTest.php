<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class authorTest extends TestCase
{
    /** @test */
    public function requesting_an_invalid_user_triggers_model_not_found_exception()
    {
        $this->withoutExceptionHandling();
        try {
            $this->json('GET', '/api/users/123');
        } catch (ModelNotFoundException $exception) {
            $this->assertEquals('No query results for model [App\User].', $exception->getMessage());
            return;
        }

        $this->fail('ModelNotFoundException should be triggered.');
    }
}
