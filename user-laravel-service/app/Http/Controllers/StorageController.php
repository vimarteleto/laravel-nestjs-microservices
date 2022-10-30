<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateStorageRequest;
use App\Http\Requests\PutObjectStorageRequest;
use App\Services\S3Service;


class StorageController extends Controller
{
    public function __construct(S3Service $service)
    {
        $this->service = $service;
    }

    public function list(Request $request)
    {
        return $this->service->listBuckets($request);
    }

    public function create(CreateStorageRequest $request)
    {
        return $this->service->createBucket($request);
    }

    public function store(PutObjectStorageRequest $request, $storage)
    {
        return $this->service->putObject($request, $storage);
    }

}
