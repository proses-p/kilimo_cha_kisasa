<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $users = User::query()
                ->when($q, fn($qbuilder) => $qbuilder->where(function($b) use ($q){
                    $b->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%");
                }))
                ->orderBy('created_at','desc')
                ->paginate(15);

        return response()->json(['success' => true, 'data' => $users]);
    }

    public function show(User $user)
    {
        return response()->json(['success' => true, 'data' => $user]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->only(['name','email','phone','role']));

        return response()->json(['success' => true, 'message' => 'User updated', 'data' => $user]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['success' => true, 'message' => 'User deleted']);
    }
}
