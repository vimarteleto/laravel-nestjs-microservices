<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\User;
use App\Services\UserService;
use App\Http\Requests\StoreUserRequest;


class DeleteUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Array $data;
    private UserService $service;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserService $service)
    {
        foreach ($this->data as $user) {
            echo 'Executing job' . PHP_EOL;
            $service->deleteUser($user['id']);
        }
    }
}
