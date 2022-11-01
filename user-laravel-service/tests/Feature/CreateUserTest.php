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
        $response->assertCreated();
    }

    public function test_create_user_with_invalid_company_cnpj()
    {
        $user = User::factory()->definition();
        $user['company_cnpj'] = 'invalid cnpj';
        $response = $this->post('/api/users', $user, ['Accept' => 'application/json']);
        $response->assertStatus(400);
    }

    public function test_create_user_with_email_already_taken()
    {
        $user = User::factory()->create();
        $response = $this->post('/api/users', $user->toArray(), ['Accept' => 'application/json']);
        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Invalid request',
            'data' => [
                'email' => [
                    'The email has already been taken.'
                ]
            ]
        ]);
    }
}
