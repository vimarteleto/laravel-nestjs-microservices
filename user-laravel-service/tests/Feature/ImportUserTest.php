<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ImportUserTest extends TestCase
{

    public function test_import_user()
    {
        $file = UploadedFile::fake()->create('users.csv');
        $response = $this->post('/api/users/import', [
            'file' => $file
        ]);
        $response->assertSuccessful();
    }

    public function test_import_user_without_file()
    {
        $response = $this->post('/api/users/import');
        $response->assertStatus(400);
    }

    public function test_import_user_with_invalid_file_format()
    {
        $file = UploadedFile::fake()->create('users.jpg');
        $response = $this->post('/api/users/import', [
            'file' => $file
        ]);
        $response->assertStatus(400);
    }
}
