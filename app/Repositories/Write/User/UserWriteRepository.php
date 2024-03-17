<?php

namespace App\Repositories\Write\User;

use App\Models\User\User;
use App\Exceptions\SavingErrorException;

class UserWriteRepository implements UserWriteRepositoryInterface
{
    public function save(User $user): bool
    {
        if (!$user->save()) {
            throw new SavingErrorException();
        }

        return true;
    }
}
