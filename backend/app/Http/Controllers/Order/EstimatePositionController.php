<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\EstimatePositionRequest;
use App\Http\Resources\Order\EstimatePositionResource;
use App\Http\Responses\DeletedJsonResponse;
use App\Http\Responses\NotExistsJsonResponse;
use App\Models\Order\EstimatePosition;
use App\Models\Order\Order;
use App\Models\Order\PaymentPeriod;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EstimatePositionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * @throws Exception
     */
    public function store(EstimatePositionRequest $request): EstimatePositionResource|NotExistsJsonResponse
    {
        $attr = $request->validated();
        if (!isset($attr['currency_increase']) || blank($attr['currency_increase'])) {
            $attr['currency_increase'] = 0;
        }
        if (!Auth::user()->is_admin &&
            !Order::whereHas('estimates', function ($q) use ($attr) {
                $q->where('id', $attr['estimate_id']);
            })->where('user_id', Auth::id())->exists()) {
            return new NotExistsJsonResponse();
        }

        try {
            DB::beginTransaction();
            $estimatePosition = EstimatePosition::create($attr);
            if ($attr['payment_periods'] !== null) {
                foreach ($attr['payment_periods'] as $paymentPeriodAttr) {
                    $paymentPeriodAttr['estimate_position_id'] = $estimatePosition->id;
                    $paymentPeriodAttr['is_income'] = $attr['is_income'];
                    PaymentPeriod::create($paymentPeriodAttr);
                }
            }
            $estimatePosition->load('position');
            $estimatePosition->load('payment_periods');
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return new EstimatePositionResource($estimatePosition);
    }

    /**
     * Update the specified resource in storage.
     * @throws Exception
     */
    public function update(EstimatePositionRequest $request, EstimatePosition $estimatePosition): EstimatePositionResource|NotExistsJsonResponse
    {
        $attr = $request->validated();
        if (!isset($attr['currency_increase']) || blank($attr['currency_increase'])) {
            $attr['currency_increase'] = 0;
        }
        if (!Auth::user()->is_admin &&
            !Order::whereHas('estimates', function ($q) use ($attr) {
                $q->where('id', $attr['estimate_id']);
            })->where('user_id', Auth::id())->exists()) {
            return new NotExistsJsonResponse();
        }
        try {
            DB::beginTransaction();
            $estimatePosition->update($attr);
            PaymentPeriod::where('estimate_position_id', $estimatePosition->id)->delete();
            if ($attr['payment_periods'] !== null) {
                foreach ($attr['payment_periods'] as $paymentPeriodAttr) {
                    $paymentPeriodAttr['estimate_position_id'] = $estimatePosition->id;
                    $paymentPeriodAttr['is_income'] = $attr['is_income'];
                    PaymentPeriod::create($paymentPeriodAttr);
                }
            }
            $estimatePosition->load('position');
            $estimatePosition->load('media');
            $estimatePosition->load('payment_periods');
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return new EstimatePositionResource($estimatePosition);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EstimatePosition $estimatePosition): DeletedJsonResponse|NotExistsJsonResponse
    {
        if (!Auth::user()->is_admin &&
            !Order::whereHas('estimates', function ($q) use ($estimatePosition) {
                $q->where('id', $estimatePosition->estimate_id);
            })->where('user_id', Auth::id())->exists()) {
            return new NotExistsJsonResponse();
        }
        $estimatePosition->delete();
        return new DeletedJsonResponse();
    }
}
