<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use App\Http\Requests\StoreFarmRequest;
use App\Http\Requests\UpdateFarmRequest;

class FarmController extends Controller
{
    public function index()
    {
        $farms = Farm::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return response()->json(['success' => true, 'data' => $farms]);
    }

    public function store(StoreFarmRequest $request)
    {
        $farm = Farm::create($request->validated());
        return response()->json(['success' => true, 'message' => 'Farm created', 'data' => $farm], 201);
    }

    public function show(Farm $farm)
    {
        $farm->load('user');
        return response()->json(['success' => true, 'data' => $farm]);
    }

    public function update(UpdateFarmRequest $request, Farm $farm)
    {
        $farm->update($request->validated());
        return response()->json(['success' => true, 'message' => 'Farm updated', 'data' => $farm]);
    }

    public function destroy(Farm $farm)
    {
        $farm->delete();
        return response()->json(['success' => true, 'message' => 'Farm deleted']);
    }
}
