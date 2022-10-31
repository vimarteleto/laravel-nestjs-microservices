<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CompanyService
{
    private $url = env('COMPANIES_SERVICE_ENDPOINT');

    public function getCompanyById($id)
    {
        $company = Http::get("{$this->url}/api/companies/$id");
        return $company->json();
    }
}
