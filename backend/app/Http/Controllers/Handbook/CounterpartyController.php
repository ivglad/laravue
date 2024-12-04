<?php

namespace App\Http\Controllers\Handbook;

use App\Http\Controllers\Controller;
use App\Http\Requests\Handbook\CounterpartyRequest;
use App\Http\Requests\Handbook\MassDestroyCounterpartyRequest;
use App\Http\Requests\Handbook\MergerCounterpartyRequest;
use App\Http\Resources\Handbook\CounterpartyCollection;
use App\Http\Resources\Handbook\CounterpartyResource;
use App\Http\Responses\DeletedJsonResponse;
use App\Http\Responses\MergedJsonResponse;
use App\Models\Handbook\Counterparty;
use App\Models\Order\Estimate;
use App\Models\Order\Order;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CounterpartyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): CounterpartyCollection
    {
        return new CounterpartyCollection(Counterparty::filter()->paginate($this->perPage));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CounterpartyRequest $request): CounterpartyResource
    {
        $counterparty = Counterparty::create($request->validated());
        return new CounterpartyResource($counterparty);
    }

    /**
     * Display the specified resource.
     */
    public function show(Counterparty $counterparty): CounterpartyResource
    {
        return new CounterpartyResource($counterparty);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CounterpartyRequest $request, Counterparty $counterparty): CounterpartyResource
    {
        $counterparty->update($request->validated());
        return new CounterpartyResource($counterparty);
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function merger(MergerCounterpartyRequest $request, Counterparty $counterparty): MergedJsonResponse
    {
        $attr = $request->validated();
        if (blank($counterparty->external_id)) {
            throw ValidationException::withMessages(['is_external' => 'Объединяющий контрагент не из 1С']);
        }
        if (Counterparty::whereIn('id', $attr['ids'])->where('external_id', '!=', null)->exists()) {
            throw ValidationException::withMessages(['is_external' => 'В выборке контрагентов для объединения есть контрагенты из 1С']);
        }
        try {
            DB::beginTransaction();
            Estimate::whereIn('counterparty_to_id', $attr['ids'])->update(['counterparty_to_id' => $counterparty->id]);
            Estimate::whereIn('counterparty_from_id', $attr['ids'])->update(['counterparty_from_id' => $counterparty->id]);
            Order::whereIn('counterparty_id', $attr['ids'])->update(['counterparty_id' => $counterparty->id]);
            Counterparty::whereIn('id', $attr['ids'])->delete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return new MergedJsonResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MassDestroyCounterpartyRequest $request): DeletedJsonResponse
    {
        $attr = $request->validated();
        Counterparty::whereIn('id', $attr['ids'])->delete();

        return new DeletedJsonResponse();
    }
}
