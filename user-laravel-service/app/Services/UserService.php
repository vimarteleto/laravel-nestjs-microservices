<?php

namespace App\Services;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\PutObjectStorageRequest;
use App\Models\User;
use App\Services\CompanyService;
use App\Services\S3Service;
use App\Services\SqsService;
use App\Jobs\CreateUserJob;
use Aws\Sqs\Exception\SqsException;

class UserService
{
    private User $model;
    private CompanyService $companyService;
    private S3Service $s3Service;
    private SqsService $sqsService;

    public function __construct(
        User $model,
        CompanyService $companyService,
        S3Service $s3Service,
        SqsService $sqsService
    ) {
        $this->model = $model;
        $this->companyService = $companyService;
        $this->s3Service = $s3Service;
        $this->sqsService = $sqsService;
    }

    public function getUsers()
    {
        return $this->model->simplePaginate(10);
    }

    public function getUserById($id)
    {
        return $this->model->find($id);
    }

    public function createUser(StoreUserRequest $request)
    {
        try {
            $cnpj = $request->company_cnpj;
            $company = $this->companyService->getCompanyByCnpj($cnpj);

            if(empty($company['cnpj'])) {
                return false;
            }

            $user = $this->model->create([
                'name' => $request->name,
                'email' => $request->email,
                'company_cnpj' => $cnpj,
            ]);
        } catch (\Throwable $th) {
            return ['message' => $th->getMessage()];
        }
        return $user;
    }

    public function updateUser(UpdateUserRequest $request, $id)
    {
        $user = $this->model->find($id);
        if ($user) {
            $user->update($request->all());
        }
        return $user;
    }

    public function deleteUser($id)
    {
        $user = $this->model->destroy($id);
        return $user;
    }

    public function deleteUsersByCompanyCnpj($cnpj)
    {
        $this->model->where('company_cnpj', $cnpj)->delete();
    }

    public function importUserFromFile(PutObjectStorageRequest $request)
    {

        if (!$this->queueExists(env('SQS_QUEUE'))) {
            return 'queue';
        }

        if (!$this->bucketExists(env('AWS_BUCKET'))) {
            return 'bucket';
        }

        try {
            $filename = $request->file;
            if (!file_exists($filename) || !is_readable($filename)) {
                return ['message' => 'Cant read file'];
            }

            $header = null;
            $users = [];
            if (($handle = fopen($filename, 'r')) !== false) {
                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    if (!$header) {
                        $header = $row;
                    }
                    else {
                        $users[] = array_combine($header, $row);
                    }
                }
                fclose($handle);
            }

            CreateUserJob::dispatch($users)->onConnection('sqs')->onQueue(env('SQS_QUEUE'));
            $object = $this->s3Service->putObjectOnBucket($request);

        } catch (SqsException  $e) {
            return ['message' => $e->getAwsErrorMessage()];
        } catch (\Throwable $th) {
            return ['message' => $th->getMessage()];
        }

        return [
            'message' => 'File sent for processing successfully',
            'uri' => $object['uri']
        ];
    }

    private function queueExists($queueName)
    {
        $queuesExists = false;
        $queues = $this->sqsService->listQueues()->getData();

        foreach ($queues->queues as $queue) {
            if ($queue == env('SQS_PREFIX') . '/' . $queueName) {
                $queuesExists = true;
            }
        }
        return $queuesExists;
    }

    private function bucketExists($bucketName)
    {
        $bucketExists = false;
        $buckets = $this->s3Service->listBuckets()->getData();

        foreach ($buckets as $bucket) {
            if ($bucket->Name == $bucketName) {
                $bucketExists = true;
            }
        }
        return $bucketExists;
    }
}