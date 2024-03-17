<?php

namespace App\Exceptions\User;

use App\Exceptions\BusinessLogicException;

class UserNotFoundException extends BusinessLogicException
{
    public function getStatus(): int
    {
        return BusinessLogicException::USER_NOT_FOUND;
    }

    public function getStatusMessage(): string
    {
        return __('errors.user_not_found');
    }
}
