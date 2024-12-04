<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class NotExistsJsonResponse extends JsonResponse
{
    public function __construct()
    {
        parent::__construct(['message' => 'Такой записи или параметра не существует'], 404);
    }
}
