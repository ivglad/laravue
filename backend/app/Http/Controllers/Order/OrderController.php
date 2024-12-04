<?php

namespace App\Http\Controllers\Order;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderMassDestroyRequest;
use App\Http\Requests\Order\OrderRequest;
use App\Http\Requests\Order\OrderStatusRequest;
use App\Http\Resources\Order\OrderCollection;
use App\Http\Resources\Order\OrderResource;
use App\Http\Responses\AccessDeniedJsonResponse;
use App\Http\Responses\DeletedJsonResponse;
use App\Models\Order\Order;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): OrderCollection
    {
        $orders = Order::auth()->with([
            'user',
            'counterparty',
            'agreement',
        ])->filter()->paginate($this->perPage);
        return new OrderCollection($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request): OrderResource
    {
        $attr = $request->validated();
        $order = Order::create($attr);
        $order->refresh();
        $order->load(Order::RELATIONS);
        return new OrderResource($order);
    }

    /**
     * @throws Exception
     */
    public function copy(OrderRequest $request, int $orderId): OrderResource
    {
        $orderOriginal = Order::with(Order::RELATIONS)->find($orderId);
        try {
            DB::beginTransaction();
            $orderCopied = $orderOriginal->replicate();
            $orderCopied->number = null;
            $orderCopied->status = OrderStatus::Draft;
            $attr = $request->validated();
            $orderCopied->fill($attr);
            $orderCopied->push();
            $estimatesCopied = with(clone $orderOriginal->estimates)->transform(function ($item) use ($orderCopied) {
                $item->order_id = $orderCopied->id;
                unset($item->id);
                unset($item->created_at);
                unset($item->updated_at);
                return $item;
            });
            $orderCopied->estimates()->createMany($estimatesCopied->toArray());
            $orderCopied->refresh();
            foreach ($orderCopied->estimates as $i => $estimateCopied) {
                $positionsCopied = with(clone $estimatesCopied[$i]->positions)->transform(function ($item) use ($estimateCopied) {
                    $item->estimate_id = $estimateCopied->id;
                    unset($item->id);
                    unset($item->created_at);
                    unset($item->updated_at);
                    return $item;
                });
                $orderCopied->estimates[$i]->positions()->createMany($positionsCopied->toArray());
                $periodPaymentsCopied = with(clone $estimatesCopied[$i]->payment_periods)->transform(function ($item) use ($estimateCopied) {
                    $item->estimate_id = $estimateCopied->id;
                    unset($item->id);
                    unset($item->created_at);
                    unset($item->updated_at);
                    return $item;
                });
                $orderCopied->estimates[$i]->payment_periods()->createMany($periodPaymentsCopied->toArray());
                foreach ($orderCopied->estimates[$i]->positions as $ii => $positionCopied) {
                    $periodPaymentsCopied = with(clone $positionsCopied[$ii]->payment_periods)->transform(function ($item) use ($positionCopied) {
                        $item->estimate_position_id = $positionCopied->id;
                        unset($item->id);
                        unset($item->created_at);
                        unset($item->updated_at);
                        return $item;
                    });
                    $orderCopied->estimates[$i]->positions[$ii]->payment_periods()->createMany($periodPaymentsCopied->toArray());
                    $collectionFiles = $orderOriginal->estimates[$i]->positions[$ii]->media;
                    foreach ($collectionFiles as $file) {
                        $file->copy($orderCopied->estimates[$i]->positions[$ii]);
                    }
                }
            }
            $orderOriginal->media()->each(function ($item) use ($orderCopied) {
                $item->copy($orderCopied);
            });
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        // $orderCopied->estimates[$k]->positions не рефрешится, отдает null, поэтому запрашиваем снова
        $orderCopied = Order::with(Order::RELATIONS)->find($orderCopied->id);
        return new OrderResource($orderCopied);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $orderId): OrderResource
    {
        $order = Order::auth()->with(Order::RELATIONS)->where('id', $orderId)->first();
        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderRequest $request, Order $order): OrderResource
    {
        $order->update($request->validated());
        $order->load(Order::RELATIONS);
        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function changeStatus(OrderStatusRequest $request, Order $order): OrderResource|AccessDeniedJsonResponse
    {
        if (Auth::user()->is_admin || $order->user_id === Auth::id()) {
            $attr = $request->validated();
            if ($attr['status'] !== OrderStatus::Draft->value && $order->number === null) {
                $attr['number'] = Order::generateNumber();
            }
            $order->update($attr);
            return new OrderResource($order);
        }
        return new AccessDeniedJsonResponse();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderMassDestroyRequest $request): DeletedJsonResponse|AccessDeniedJsonResponse
    {
        $attr = $request->validated();
        if (!Auth::user()->is_admin) {
            $userIds = Order::whereIn('id', $attr['ids'])->pluck('user_id')->toArray();
            $userIds = array_unique($userIds);
            if (!in_array(Auth::id(), $userIds) || count($userIds) > 1) {
                return new AccessDeniedJsonResponse();
            }
        }
        Order::whereIn('id', $attr['ids'])->delete();
        return new DeletedJsonResponse();
    }
}
