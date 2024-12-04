<?php

namespace App\Http\Controllers\Report;

use App\Enums\ReportType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\ReportRequest;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    public function report(ReportRequest $request): JsonResponse
    {
        $attr = $request->validated();
        $response = (new (ReportType::from($attr['type'])->name())($attr))->run();
        return new JsonResponse(['data' => $response]);
    }
}
