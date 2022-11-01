<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    private $userJson = [
        "id",
        "name",
        "email",
        "company_cnpj",
        "created_at",
        "updated_at"
    ];


    public function test_update_user()
    {
        $user = User::factory()->create();
        $payload = User::factory()->definition();
        $response = $this->put("/api/users/{$user->id}", $payload, ['Accept' => 'application/json']);
        $response->assertJsonStructure($this->userJson);
        $response->assertSuccessful();
    }

    public function test_update_user_not_found()
    {
        $user = User::factory()->create();
        $payload = User::factory()->definition();
        $id = $user->id + 1;
        $response = $this->put("/api/users/$id", $payload, ['Accept' => 'application/json']);
        $response->assertNotFound();
    }
}
