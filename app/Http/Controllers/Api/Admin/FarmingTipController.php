<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\FarmingTip;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFarmingTipRequest;
use App\Http\Requests\UpdateFarmingTipRequest;

class FarmingTipController extends Controller
{
    public function index()
    {
        $tips = FarmingTip::orderBy('created_at','desc')->paginate(15);
        return response()->json(['success' => true, 'data' => $tips]);
    }

    public function store(StoreFarmingTipRequest $request)
    {
        $tip = FarmingTip::create($request->validated());
        return response()->json(['success' => true, 'message' => 'Tip created','data' => $tip], 201);
    }

    public function show(FarmingTip $farmingTip)
    {
        return response()->json(['success' => true, 'data' => $farmingTip]);
    }

    public function update(UpdateFarmingTipRequest $request, FarmingTip $farmingTip)
    {
        $farmingTip->update($request->validated());
        return response()->json(['success' => true, 'message' => 'Tip updated', 'data' => $farmingTip]);
    }

    public function destroy(FarmingTip $farmingTip)
    {
        $farmingTip->delete();
        return response()->json(['success' => true, 'message' => 'Tip deleted']);
    }
}
