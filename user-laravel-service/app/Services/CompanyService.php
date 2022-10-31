<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CompanyService
{
    private $url = 'http://companies:3000';

    public function getCompanyById($id)
    {
        $company = Http::get("{$this->url}/api/companies/$id");
        return $company->json();
    }
}