<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_user()
    {
        $user = User::factory()->definition();
        $response = $this->post('/api/users', $user, ['Accept' => 'application/json']);
        $response->assertStatus(201);
    }

    public function test_create_user_with_invalid_company_cnpj()
    {
        $user = User::factory()->definition();
        $user['company_cnpj'] = '123';
        $response = $this->post('/api/users', $user, ['Accept' => 'application/json']);
        $response->assertStatus(400);
    }
}
