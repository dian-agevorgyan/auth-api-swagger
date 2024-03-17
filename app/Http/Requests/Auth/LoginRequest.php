<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    const USERNAME = 'username';
    const PASSWORD = 'password';

    public function rules(): array
    {
        return [
            self::USERNAME => [
                'required',
                'string',
            ],

            self::PASSWORD => [
                'required',
                'string',
            ],
        ];
    }

    public function getUsername(): string
    {
        return $this->get(self::USERNAME);
    }

    public function getUserPassword(): string
    {
        return $this->get(self::PASSWORD);
    }
}
