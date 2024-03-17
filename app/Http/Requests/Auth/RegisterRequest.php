<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    const NAME = 'name';
    const SURNAME = 'surname';
    const USERNAME = 'username';
    const PASSWORD = 'password';

    public function rules(): array
    {
        return [
            self::NAME => [
                'required',
                'string',
            ],

            self::SURNAME => [
                'required',
                'string',
            ],

            self::USERNAME => [
                'required',
                'unique:users',
                'string',
            ],

            self::PASSWORD => [
                'required',
                'string',
                'confirmed',
            ],
        ];
    }

    public function getName(): string
    {
        return $this->get(self::NAME);
    }

    public function getSurname(): string
    {
        return $this->get(self::SURNAME);
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
