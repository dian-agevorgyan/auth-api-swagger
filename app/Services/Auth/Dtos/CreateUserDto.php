<?php

namespace App\Services\Auth\Dtos;

use App\Http\Requests\Auth\RegisterRequest;

class CreateUserDto
{
    public string $name;
    public string $surname;
    public string $username;
    public string $password;

    public static function fromRequest(RegisterRequest $request): CreateUserDto
    {
        $createUserDto = new self();

        $createUserDto->name = $request->getName();
        $createUserDto->surname = $request->getSurname();
        $createUserDto->username = $request->getUsername();
        $createUserDto->password = $request->getUserPassword();

        return $createUserDto;
    }
}
