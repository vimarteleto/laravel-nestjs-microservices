<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{

    public function test_delete_user()
    {
        $user = User::factory()->create();
        $response = $this->delete("/api/users/{$user->id}");
        $response->assertSuccessful();
    }

    public function test_delete_user_not_found()
    {
        $user = User::factory()->create();
        $id = $user->id + 1;
        $response = $this->delete("/api/users/$id");
        $response->assertNotFound();
    }
}
