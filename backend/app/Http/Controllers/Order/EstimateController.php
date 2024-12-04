<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\MassStoreEstimateRequest;
use App\Http\Requests\Order\UpdateEstimateRequest;
use App\Http\Resources\Order\EstimateCollection;
use App\Http\Resources\Order\EstimateResource;
use App\Http\Responses\AccessDeniedJsonResponse;
use App\Http\Responses\DeletedJsonResponse;
use App\Models\Order\Estimate;
use App\Models\Order\Order;
use App\Models\Order\PaymentPeriod;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EstimateController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * @throws Exception
     */
    public function massStore(MassStoreEstimateRequest $request): EstimateCollection|AccessDeniedJsonResponse
    {
        $attr = $request->validated();
        if (!Auth::user()->is_admin) {
            $orderIds = [];
            foreach ($attr['estimates'] as $estimateAttr) {
                $orderIds[] = $estimateAttr['order_id'];
            }
            $orderIds = array_unique($orderIds);
            if (count($orderIds) > 1 || !Order::whereIn('id', $orderIds)->where('user_id', Auth::id())->exists()) {
                return new AccessDeniedJsonResponse();
            }
        }
        $estimateIds = [];
        try {
            DB::beginTransaction();
            foreach ($attr['estimates'] as $estimateAttr) {
                $tmpId = Estimate::create($estimateAttr)->id;
                $estimateIds[] = $tmpId;
                if (isset($estimateAttr['payment_periods'])) {
                    foreach ($estimateAttr['payment_periods'] as $paymentPeriodAttr) {
                        $paymentPeriodAttr['estimate_id'] = $tmpId;
                        PaymentPeriod::create($paymentPeriodAttr);
                    }
                }
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        return new EstimateCollection(
            Estimate::with([
                'payment_periods',
                'counterparty_to',
                'counterparty_from',
                'positions',
            ])
                ->whereIn('id', $estimateIds)
                ->get()
        );
    }

    /**
     * Update the specified resource in storage.
     * @throws Exception
     */
    public function update(UpdateEstimateRequest $request, Estimate $estimate): EstimateResource|AccessDeniedJsonResponse
    {
        $attr = $request->validated();
        if (!Auth::user()->is_admin && Order::where('id', $attr['order_id'])->pluck('user_id')->first() !== Auth::id()) {
            return new AccessDeniedJsonResponse();
        }
        try {
            DB::beginTransaction();
            $estimate->update($attr);
            PaymentPeriod::where('estimate_id', $estimate->id)->delete();
            if ($attr['payment_periods'] !== null) {
                foreach ($attr['payment_periods'] as $paymentPeriodAttr) {
                    $paymentPeriodAttr['estimate_id'] = $estimate->id;
                    PaymentPeriod::create($paymentPeriodAttr);
                    foreach ($estimate->positions as $position) {
                        $position->payment_periods()->delete();
                    }
                }
            }
            $estimate->load('payment_periods');
            $estimate->load('counterparty_to');
            $estimate->load('counterparty_from');
            $estimate->load('positions');
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return new EstimateResource($estimate);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Estimate $estimate): DeletedJsonResponse|AccessDeniedJsonResponse
    {
        if (!Auth::user()->is_admin && Order::where('id', $estimate->order_id)->pluck('user_id')->first() !== Auth::id()) {
            return new AccessDeniedJsonResponse();
        }
        $estimate->delete();
        return new DeletedJsonResponse();
    }
}
