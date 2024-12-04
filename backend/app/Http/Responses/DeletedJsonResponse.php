<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class DeletedJsonResponse extends JsonResponse
{
    public function __construct()
    {
        parent::__construct(['message' => 'Успешно удалено']);
    }
}
