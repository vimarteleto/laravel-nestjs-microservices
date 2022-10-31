<?php

namespace App\Services;


use Illuminate\Http\Request;
use App\Http\Requests\CreateStorageRequest;
use App\Http\Requests\PutObjectStorageRequest;
use Aws\S3\S3Client;

class S3Service
{
    private S3Client $client;

    public function __construct()
    {

        $this->client = new S3Client([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT'),
        ]);
    }

    public function listBuckets(Request $request)
    {
        try {
            $buckets = [];
            $response = $this->client->listBuckets();
            foreach ($response['Buckets'] as $bucket) {
                $buckets[] = $bucket;
            }
            return response()->json($buckets);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 400);
        }
    }

    function createBucket(CreateStorageRequest $request)
    {
        try {
            $bucket = $this->client->createBucket([
                'Bucket' => $request->storage,
            ]);

            return response()->json([
                'location' => $bucket['Location'],
                'uri' => $bucket['@metadata']['effectiveUri']
            ]);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function putObject(PutObjectStorageRequest $request, $bucket = 'users-bucket')
    {
        try {
            $object = $this->client->putObject([
                'Bucket' => $bucket,
                'Key' => $request->file('file')->getClientOriginalName(),
                'SourceFile' => $request->file,
            ]);
            return ['uri' => $object['ObjectURL']];
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
