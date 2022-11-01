<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateQueueRequest;
use App\Services\SqsService;


class QueueController extends Controller
{
    public function __construct(SqsService $service)
    {
        $this->service = $service;
    }

    public function list(Request $request)
    {
        return $this->service->listQueues($request);
    }

    public function create(CreateQueueRequest $request)
    {
        return $this->service->createQueue($request);
    }
}
