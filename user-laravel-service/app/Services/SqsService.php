<?php

namespace App\Services;


use Illuminate\Http\Request;
use App\Http\Requests\CreateQueueRequest;
use Aws\Sqs\SqsClient;

class SqsService
{
    private SqsClient $client;

    public function __construct()
    {
        $this->client = new SqsClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT'),
        ]);
    }

    public function createQueue(CreateQueueRequest $request)
    {
        try {
            $queue = $this->client->createQueue([
                'QueueName' => $request->queue,
                'Attributes' => [
                    'DelaySeconds' => 5,
                ],
            ]);
            return response()->json(['url' => $queue['QueueUrl']]);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function listQueues(Request $request)
    {
        try {
            $response = $this->client->listQueues();
            return response()->json(['queues' => $response['QueueUrls']]);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
