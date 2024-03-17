<?php

namespace App\Repositories\Read\User;

interface UserReadRepositoryInterface
{
    public function getByUsername(string $username);
}
