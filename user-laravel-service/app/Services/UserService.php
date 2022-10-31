<?php

namespace App\Services;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\PutObjectStorageRequest;
use App\Models\User;
use App\Services\CompanyService;
use App\Services\S3Service;
use App\Jobs\CreateUserJob;
use Aws\Sqs\Exception\SqsException;

class UserService
{
    private User $model;
    private CompanyService $companyService;
    private S3Service $s3Service;

    public function __construct(User $model, CompanyService $companyService, S3Service $s3Service)
    {
        $this->model = $model;
        $this->companyService = $companyService;
        $this->s3Service = $s3Service;
    }

    public function getUsers()
    {
        return $this->model->paginate();
    }

    public function getUserById($id)
    {
        return $this->model->find($id);
    }

    public function createUser(StoreUserRequest $request)
    {
        try {
            $company_id = $request->company_id;
            $company = $this->companyService->getCompanyById($company_id);

            if(empty($company['_id'])) {
                return ['message' => "Company id $company_id not found"];
            }
    
            $user = $this->model->create([
                'name' => $request->name,
                'email' => $request->email,
                'company_id' => $company_id,
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

    public function deleteUserByCompanyId($id)
    {
        $this->model->where('company_id', $id)->delete();
    }

    public function importUserFromFile(PutObjectStorageRequest $request)
    {
        try {
            $filename = $request->file;
            if (!file_exists($filename) || !is_readable($filename)) {
                return ['message' => 'Cant read file'];
            }
    
            $header = null;
            $users = [];
            if (($handle = fopen($filename, 'r')) !== false) {
                while (($row = fgetcsv($handle, 1000, ',')) !== false)
                {
                    if (!$header) {
                        $header = $row;
                    }
                    else {
                        $users[] = array_combine($header, $row);
                    }
                }
                fclose($handle);
            }
    
            CreateUserJob::dispatch($users)->onConnection('sqs')->onQueue('users');
            $object = $this->s3Service->putObject($request);
            
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
}