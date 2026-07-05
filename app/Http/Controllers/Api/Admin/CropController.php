<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCropRequest;
use App\Http\Requests\UpdateCropRequest;

class CropController extends Controller
{
    public function index()
    {
        $crops = Crop::with('farm')->orderBy('created_at','desc')->paginate(15);
        return response()->json(['success' => true, 'data' => $crops]);
    }

    public function store(StoreCropRequest $request)
    {
        $crop = Crop::create($request->validated());

        return response()->json(['success' => true, 'message' => 'Crop created', 'data' => $crop], 201);
    }

    public function show(Crop $crop)
    {
        $crop->load('farm');
        return response()->json(['success' => true, 'data' => $crop]);
    }

    public function update(UpdateCropRequest $request, Crop $crop)
    {
        $crop->update($request->validated());

        return response()->json(['success' => true, 'message' => 'Crop updated', 'data' => $crop]);
    }

    public function destroy(Crop $crop)
    {
        if ($crop->image) {
            Storage::disk('public')->delete($crop->image);
        }
        $crop->delete();
        return response()->json(['success' => true, 'message' => 'Crop deleted']);
    }
}
