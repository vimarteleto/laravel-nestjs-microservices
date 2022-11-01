<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetUserTest extends TestCase
{

    private $paginationJson = [
        "current_page",
        "data",
        "first_page_url",
        "from",
        "next_page_url",
        "path",
        "per_page",
        "prev_page_url",
        "to"
    ];

    private $userJson = [
        "id",
        "name",
        "email",
        "company_cnpj",
        "created_at",
        "updated_at"
    ];

    public function test_list_all_users()
    {
        User::factory()->create();
        User::factory()->create();
        User::factory()->create();
        $response = $this->get('/api/users');
        $response->assertJsonStructure($this->paginationJson);
        $response->assertSuccessful();
    }

    public function test_list_user_by_id()
    {
        $user = User::factory()->create();
        $response = $this->get("/api/users/{$user->id}");
        $response->assertJsonStructure($this->userJson);
        $response->assertSuccessful();
    }
}
