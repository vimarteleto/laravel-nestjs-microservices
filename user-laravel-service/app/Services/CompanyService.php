<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CompanyService
{
    private $url = 'http://nestjs:3000';

    public function getCompanyById($id)
    {
        $company = Http::get("{$this->url}/api/users/$id");
        return $company->json();
    }
}