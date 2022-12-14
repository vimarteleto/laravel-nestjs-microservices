<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\PutObjectStorageRequest;
use App\Services\UserService;

class UserController extends Controller
{
    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->service->getUsers();
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->service->createUser($request);
        if (!$user) {
            return response()->json(['message' => "Company CNPJ {$request->company_cnpj} not found"], 404);
        }
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->service->getUserById($id);
        if (!$user) {
            return response()->json(['message' => "User id #$id not found"], 404);
        }
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = $this->service->updateUser($request, $id);
        if (!$user) {
            return response()->json(['message' => "User id #$id not found"], 404);
        }
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->service->deleteUser($id);
        if (!$user) {
            return response()->json(['message' => "User id #$id not found"], 404);
        }
        return response()->json(['message' => "User id #$id deleted successfully"]);
    }

    public function import(PutObjectStorageRequest $request)
    {
        $import = $this->service->importUserFromFile($request);
        if ($import == 'queue') {
            return response()->json(['message' => "Queue " . env('SQS_QUEUE') . " not found"], 404);
        }
        if ($import == 'bucket') {
            return response()->json(['message' => "Bucket " . env('AWS_BUCKET') . " not found"], 404);
        }
        return response()->json($import);
    }

    public function deleteUsersByCompanyCnpj(Request $request, $cnpj)
    {
        $this->service->deleteUsersByCompanyCnpj($cnpj);
    }
}
