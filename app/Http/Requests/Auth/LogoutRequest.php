<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LogoutRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function getAuthUser()
    {
        return $this->user();
    }
}
