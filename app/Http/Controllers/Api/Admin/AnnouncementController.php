<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderBy('created_at','desc')->paginate(15);
        return response()->json(['success' => true, 'data' => $announcements]);
    }

    public function store(StoreAnnouncementRequest $request)
    {
        $announcement = Announcement::create($request->validated());
        return response()->json(['success' => true, 'message' => 'Announcement created','data' => $announcement], 201);
    }

    public function show(Announcement $announcement)
    {
        return response()->json(['success' => true, 'data' => $announcement]);
    }

    public function update(UpdateAnnouncementRequest $request, Announcement $announcement)
    {
        $announcement->update($request->validated());
        return response()->json(['success' => true, 'message' => 'Announcement updated', 'data' => $announcement]);
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return response()->json(['success' => true, 'message' => 'Announcement deleted']);
    }
}
