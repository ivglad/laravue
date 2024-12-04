<?php

namespace App\Http\Controllers\Handbook;

use App\Http\Controllers\Controller;
use App\Http\Requests\Handbook\PositionRequest;
use App\Http\Resources\Handbook\PositionCollection;
use App\Http\Resources\Handbook\PositionResource;
use App\Http\Responses\DeletedJsonResponse;
use App\Models\Handbook\Position;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): PositionCollection
    {
        return new PositionCollection(Position::filter()->paginate($this->perPage));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PositionRequest $request): PositionResource
    {
        $position = Position::create($request->validated());
        return new PositionResource($position);
    }

    /**
     * Display the specified resource.
     */
    public function show(Position $position): PositionResource
    {
        return new PositionResource($position);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PositionRequest $request, Position $position): PositionResource
    {
        $position->update($request->validated());
        return new PositionResource($position);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position): DeletedJsonResponse
    {
        $position->delete();
        return new DeletedJsonResponse();
    }
}
