<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Get only the logged-in resident's complaints
        $complaints = Complaint::where('user_id', $userId)
            ->latest()
            ->get();

        // Resident complaint statistics
        $stats = [
            'total' => $complaints->count(),

            'pending' => $complaints->where('status', 'pending')->count(),

            'in_progress' => $complaints->where('status', 'in_progress')->count(),

            'resolved' => $complaints->where('status', 'resolved')->count(),
        ];

        return view('resident.dashboard', compact('complaints', 'stats'));
    }
}