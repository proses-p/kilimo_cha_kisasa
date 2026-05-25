<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use App\Models\Crop;
use Illuminate\Http\Request;

class CropController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Farm $farm)
    {
        if ($farm->user_id !== $request->user()->id){
            return response()->json([
                'success' => false,
                'message' => 'Huna ruhusa!',
            ], 403);
        }

        $crops = $farm->crops()
                      ->withCount('activities')
                      ->latest()
                      ->get();
        return response()->json([
            'success' => true,
            'data' => $crops,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Farm $farm)
    {
        if ($farm->user_id !== $request->user()->id){
            return response()->json([
                'success' => false,
                'message' => 'No option to change this farm',
            ], 403);
        }

        $validated = $request->validate([
            'crop_name' => 'required|string|max:100',
            'planting_date' => 'required|date',
            'harvest_date' => 'nullable|date|after:planting_date',
            'status' => 'sometimes|in:planted,growing,harvested,failed',
            'notes' => 'nullable|string',
        ]);

        $crop = $farm->crops()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Zao limeongezwa vizuri',
            'data' => $crop,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Farm $farm, Crop $crop)
    {
        if ($farm->user_id !== $request->user()->id){
            return response()->json([
                'success' => false,
                'message' => 'Huna ruhusa',
            ], 403);
        }

        $crop->load('activities');

        // add days remain to harvest
        $crop->days_to_harvest = $crop->daysToHarvest();

        return response()->json([
            'success' => true,
            'data' => $crop,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Farm $farm, Crop $crop)
    {
        if ($farm->user_id !== $request->user()->id){
            return response()->json([
                'success' => false,
                'message' => 'No option to change this farm',
            ], 403);
        }

        $validated = $request->validate([
            'crop_name' => 'required|string|max:100',
            'planting_date' => 'required|date',
            'harvest_date' => 'nullable|date|after:planting_date',
            'status' => 'sometimes|in:planted,growing,harvested,failed',
            'notes' => 'nullable|string',
        ]);

        $crop->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Zao limesasishwa',
            'data' => $crop,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Farm $farm, Crop $crop)
    {
        if ($farm->user_id !== $request->user()->id){
            return response()->json([
                'success' => false,
                'message' => 'No option to change this farm',
            ], 403);
        }

        $crop->delete();
        return response()->json([
            'success' => true,
            'message' => 'Zao limefutwa',
        ]);
    }
}
