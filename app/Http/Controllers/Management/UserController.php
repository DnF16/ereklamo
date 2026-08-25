<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of users for management.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        $counts = [
            'total' => $users->count(),
            'residents' => $users->where('role', 'resident')->count(),
            'staff' => $users->where('role', 'staff')->count(),
            'admins' => $users->where('role', 'admin')->count(),
        ];

        return view('management.users', compact('users', 'counts'));
    }

    /**
     * Store a newly created user (staff/resident/admin).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:50',
            'role' => 'required|string|in:resident,staff,admin',
            'password' => 'required|confirmed|min:6',
            'address' => 'nullable|string',
        ]);

        // The User model casts the password to hashed, so assigning raw is OK.
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'],
            'contact_number' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }
}
