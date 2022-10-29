<?php

namespace App\Services;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserService
{
    private User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getUsers()
    {
        return $this->model->all();
    }

    public function getUserById($id)
    {
        return $this->model->find($id);
    }

    public function createUser(StoreUserRequest $request)
    {
        $user = $this->model->create([
            'name' => $request->name,
            'email' => $request->email,
            'company_id' => $request->company_id,
        ]);

        return $user;
    }

    public function updateUser(UpdateUserRequest $request, $id)
    {
        $user = $this->model->find($id);
        $user ?: $user->update($request->all());
        return $user;
    }

    public function deleteUser($id)
    {
        $user = $this->model->delete($id);
        return $user;
    }
}