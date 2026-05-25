<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use Illuminate\Http\Request;

class FarmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $farms = $request->user()
                         ->farms()
                         ->withCount('crops')
                         ->latest()
                         ->get();
        return response()->json([
            'success' => true,
            'data' => $farms,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'size_acres' => 'required|numeric|min:0.1',
            'soil_type' => 'required|in:clay,sandy,loamy,silty,peaty',
        ]);

        $farm = $request->user()->farms()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'farm added successfully',
            'data' => $farm,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Farm $farm)
    {
        // make sure this farm its for the user
        if ($farm->user_id !== $request->user()->id){
            return response()->json([
                'success' => false,
                'message' => 'No option to see this farm',
            ], 403);
        }

        // pakia crops na activities zake

        $farm->load('crops.activities');

        return response()->json([
            'success' => true,
            'data' => $farm,
        ]);


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Farm $farm)
    {
        //
        if ($farm->user_id !== $request->user()->id){
            return response()->json([
                'success' => false,
                'message' => 'No option to change this farm',
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'size_acres' => 'required|numeric|min:0.1',
            'soil_type' => 'required|in:clay,sandy,loamy,silty,peaty',
        ]);

        $farm->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'shamba limesasishwa vizuri',
            'data' => $farm,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Farm $farm)
    {
        //
        if ($farm->user_id !== $request->user()->id){
            return response()->json([
                'success' => false,
                'message' => 'No option to change this farm',
            ], 403);if ($farm->user_id !== $request->user()->id){
            return response()->json([
                'success' => false,
                'message' => 'No option to change this farm',
            ], 403);
        }
        }if ($farm->user_id !== $request->user()->id){
            return response()->json([
                'success' => false,
                'message' => 'No option to delete this farm',
            ], 403);
        }

        $farm->delete();

        return response()->json([
            'success' => true,
            'message' => 'shamba limefutwa',
        ]);
    }
}
