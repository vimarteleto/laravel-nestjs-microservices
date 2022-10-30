<?php

namespace App\Services;


use Illuminate\Http\Request;
use App\Http\Requests\CreateStorageRequest;
use App\Http\Requests\PutObjectStorageRequest;
use Aws\S3\S3Client;

class S3Service
{
    private S3Client $client;

    public function __construct() {

        $this->client = new S3Client([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'endpoint' => 'http://localstack:4566',
            'use_path_style_endpoint' => true,
        ]);
    }

    public function listBuckets(Request $request)
    {
        $buckets = [];
        $response = $this->client->listBuckets();
        foreach ($response['Buckets'] as $bucket) {
            $buckets[] = $bucket;
        }
        return response()->json($buckets);
    }

    function createBucket(CreateStorageRequest $request)
    {
        try {
            $result = $this->client->createBucket([
                'Bucket' => $request->storage,
            ]);

            return response()->json([
                'location' => $result['Location'],
                'uri' => $result['@metadata']['effectiveUri']
            ]);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function putObject(PutObjectStorageRequest $request, $bucket = 'users-bucket')
    {
        try {
            $result = $this->client->putObject([
                'Bucket' => $bucket,
                'Key' => $request->file('file')->getClientOriginalName(),
                'SourceFile' => $request->file,
            ]);
            return ['uri' => $result['ObjectURL']];
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}