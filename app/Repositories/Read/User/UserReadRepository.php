<?php

namespace App\Repositories\Read\User;

use App\Exceptions\User\UserNotFoundException;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Builder;

class UserReadRepository implements UserReadRepositoryInterface
{
    private function query(): Builder
    {
        return User::query();
    }

    public function getByUsername(string $username): User
    {
        $user = $this->query()
            ->where('username', $username)
            ->first();

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}
