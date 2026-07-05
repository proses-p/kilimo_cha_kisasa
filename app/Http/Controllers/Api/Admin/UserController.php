<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
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

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['role'] = $data['role'] ?? 'user';
        $data['is_active'] = $data['is_active'] ?? true;

        $user = User::create($data);

        return response()->json(['success' => true, 'message' => 'User created', 'data' => $user], 201);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->only(['name','email','phone','role','is_active']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $user->update($data);

        return response()->json(['success' => true, 'message' => 'User updated', 'data' => $user]);
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'You cannot delete your own account.'], 403);
        }

        $user->delete();
        return response()->json(['success' => true, 'message' => 'User deleted']);
    }
}
