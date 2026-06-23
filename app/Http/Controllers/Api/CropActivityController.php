<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use App\Models\CropActivity;
use Illuminate\Http\Request;

class CropActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Crop $crop)
    {
        if ($crop->farm_id->user_id !== $request->user()->id){
            return response()->json([
                'success' => false,
                'message' => 'Huna ruhusa',
            ], 403);
        }

        $activities = $crop->activities()
                           ->latest('activity_date')
                           ->get();
        return response()->json([
            'success' => true,
            'data' => $activities,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Crop $crop)
    {
        if ($crop->farm->user_id !== $request->user()->id){
            return response()->json([
                'success' => false,
                'message' => 'Huna ruhusa',
            ], 403);
        }

        $validated = $request->validate([
            'activity_type' => 'required|in:watering,fertilizing,weeding,spraying,pruning',
            'description' => 'nullable|string',
            'activity_date' => 'required|date',
        ]);

        $validated['scheduled_date'] = $validated['activity_date'];
        $validated['is_completed'] = false;


        $activity = $crop->activities()->create($validated);
        return response()->json([
            'success' => true,
            'message' => 'Shughuli imeongezwa',
            'data' => $activity,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Crop $crop, CropActivity $activity)
    {
        //
        if ($crop->farm_id->user_id !== $request->user()->id){
            return response()->json([
                'success' => false,
                'message' => 'Huna ruhusa',
            ], 403);
        }

        $activity->delete();
        return response()->json([
            'success' => true,
            'message' => 'Shughuli imefutwa',
        ]);
    
    }
}
