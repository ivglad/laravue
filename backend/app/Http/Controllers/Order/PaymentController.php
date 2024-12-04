<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StorePaymentRequest;
use App\Http\Resources\Order\PaymentResource;
use App\Http\Responses\NotExistsJsonResponse;
use App\Models\Order\Order;
use App\Models\Order\Payment;
use App\Models\Order\PaymentPeriod;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request): PaymentResource|NotExistsJsonResponse
    {
        $attr = $request->validated();
        $pp = PaymentPeriod::where('id', $attr['payment_period_id'])->first();
        if (!Auth::user()->is_admin &&
            !Order::whereHas('estimates', function ($q) use ($pp) {
                if (!blank($pp->estimate_position_id)) {
                    $q->where('id', $pp->estimate_position->estimate_id);
                }
                else {
                    $q->where('id', $pp->estimate_id);
                }
            })->where('user_id', Auth::id())->exists()) {
            return new NotExistsJsonResponse();
        }
        $payment = Payment::create($attr);
        return new PaymentResource($payment);
    }
}
