<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateStorageRequest;
use App\Http\Requests\PutObjectStorageRequest;
use App\Services\SqsService;


class QueueController extends Controller
{
    public function __construct(SqsService $service)
    {
        $this->service = $service;
    }

    public function create(Request $request)
    {
        return $this->service->createQueue($request);
    }
}
