<?php

namespace App\Http\Resources\Order;

use App\Enums\OrderStatus;
use App\Models\Order\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class OrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection
        ];
    }

    public function with(Request $request)
    {
        if (Auth::user()->is_admin) {
            $counts = [
                'all' => Order::count(),
                'draft' => Order::where('status', OrderStatus::Draft)->count(),
                'in_work' => Order::where('status', OrderStatus::InWork)->count(),
                'archive' => Order::where('status', OrderStatus::Archive)->count(),
            ];
        }
        else {
            $counts = [
                'all' => Order::where('user_id', Auth::id())->count(),
                'draft' => Order::where('user_id', Auth::id())->where('status', OrderStatus::Draft)->count(),
                'in_work' => Order::where('user_id', Auth::id())->where('status', OrderStatus::InWork)->count(),
                'archive' => Order::where('user_id', Auth::id())->where('status', OrderStatus::Archive)->count(),
            ];
        }
        return [
            'meta' => [
                'counts' => $counts
            ]
        ];
    }
}
