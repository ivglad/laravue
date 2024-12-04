<?php

declare(strict_types=1);

namespace App\Http\Controllers\Handbook;

use App\Enums\CurrencyCode;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Agreement\Agreement;
use App\Models\Handbook\Counterparty;
use App\Models\Handbook\Position;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class HandbookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $counterpartiesManual = Counterparty::where('external_id', null)->select('id', 'name')->get();
        $counterpartiesExternal = Counterparty::where('external_id', '!=', null)->select('id', 'name')->get();

        return new JsonResponse([
            'data' => [
                'order_statuses' => OrderStatus::objects(),
                'counterparties' => [
                    'manual' => $counterpartiesManual,
                    'external' => $counterpartiesExternal,
                ],
                'currency_codes' => CurrencyCode::objects(),
                'positions' => Position::select('id', 'name', 'type', 'is_tax', 'is_transport')->get(),
                'agreements' => Agreement::select('id', 'number', 'name')->get(),
                'users' => User::select('id', 'name', 'surname', 'patronymic', 'hex_color')->get(),
            ],
        ]);
    }
}
