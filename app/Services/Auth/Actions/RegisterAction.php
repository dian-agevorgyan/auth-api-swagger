<?php

namespace App\Services\Auth\Actions;

use App\Models\User\User;
use App\Services\Auth\Dtos\CreateUserDto;
use App\Repositories\Write\User\UserWriteRepositoryInterface;

class RegisterAction
{
    public function __construct(protected UserWriteRepositoryInterface $userWriteRepository) {}

    public function run(CreateUserDto $dto): void
    {
        $user = User::staticCreate($dto);
        $this->userWriteRepository->save($user);
    }
}
