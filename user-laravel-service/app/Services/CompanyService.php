<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CompanyService
{
    public function getCompanyByCnpj($cnpj)
    {
        $company = Http::get(env('COMPANIES_SERVICE_ENDPOINT') . "/api/companies/$cnpj");
        return $company->json();
    }
}
