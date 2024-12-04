<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class AccessDeniedJsonResponse extends JsonResponse
{
    public function __construct()
    {
        parent::__construct(['message' => 'Доступ запрещен'], 403);
    }
}
