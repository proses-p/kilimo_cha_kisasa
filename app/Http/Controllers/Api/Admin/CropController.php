<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCropRequest;
use App\Http\Requests\UpdateCropRequest;
use Illuminate\Support\Facades\Storage;

class CropController extends Controller
{
    public function index()
    {
        $crops = Crop::orderBy('created_at','desc')->paginate(15);
        return response()->json(['success' => true, 'data' => $crops]);
    }

    public function store(StoreCropRequest $request)
    {
        $data = $request->only(['name','description','planting_season','harvesting_period']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('crops','public');
            $data['image'] = $path;
        }

        $crop = Crop::create($data);

        return response()->json(['success' => true, 'message' => 'Crop created', 'data' => $crop], 201);
    }

    public function show(Crop $crop)
    {
        return response()->json(['success' => true, 'data' => $crop]);
    }

    public function update(UpdateCropRequest $request, Crop $crop)
    {
        $data = $request->only(['name','description','planting_season','harvesting_period']);
        if ($request->hasFile('image')) {
            // delete old
            if ($crop->image) {
                Storage::disk('public')->delete($crop->image);
            }
            $data['image'] = $request->file('image')->store('crops','public');
        }

        $crop->update($data);

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
