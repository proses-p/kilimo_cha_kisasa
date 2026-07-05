<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Crop;
use App\Models\Farm;
use App\Models\FarmingTip;
use App\Models\Announcement;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats()
    {
        $data = [
            'total_users' => User::count(),
            'total_farms' => Farm::count(),
            'total_crops' => Crop::count(),
            'total_tips' => FarmingTip::count(),
            'total_announcements' => Announcement::count(),
            'recent_users' => User::orderBy('created_at', 'desc')->take(5)->get(),
            'recent_farms' => Farm::with('user')->orderBy('created_at', 'desc')->take(5)->get(),
            'recent_activities' => collect([
                'crops' => Crop::orderBy('created_at','desc')->take(5)->get(),
                'tips' => FarmingTip::orderBy('created_at','desc')->take(5)->get(),
                'announcements' => Announcement::orderBy('created_at','desc')->take(5)->get(),
            ]),
        ];

        return response()->json(['success' => true, 'data' => $data]);
    }
}
