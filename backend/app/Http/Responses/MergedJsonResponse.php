<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class MergedJsonResponse extends JsonResponse
{
    public function __construct()
    {
        parent::__construct(['message' => 'Успешно объединены']);
    }
}
