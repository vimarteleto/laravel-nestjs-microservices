<?php

namespace App\Services;


use Illuminate\Http\Request;
use App\Http\Requests\CreateStorageRequest;
use App\Http\Requests\PutObjectStorageRequest;
use Aws\Sqs\SqsClient;

class SqsService
{
    private SqsClient $client;

    public function __construct() {

        $this->client = new SqsClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'endpoint' => 'http://localstack:4566',
            'use_path_style_endpoint' => true,
        ]);
    }

    public function createQueue(Request $request)
    {
        try {
            $result = $this->client->createQueue([
                'QueueName' => $request->queue,
                'Attributes' => [
                    'DelaySeconds' => 5,
                ],
            ]);
            var_dump($result);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

}