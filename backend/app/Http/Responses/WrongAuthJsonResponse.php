<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class WrongAuthJsonResponse extends JsonResponse
{
    public function __construct()
    {
        parent::__construct(['message' => 'Неправильный логин или пароль'], 401);
    }
}
