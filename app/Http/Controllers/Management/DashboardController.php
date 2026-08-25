<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get complaint statistics
        $stats = [
            'total' => Complaint::count(),
            'pending' => Complaint::where('status', 'pending')->count(),
            'in_progress' => Complaint::where('status', 'in_progress')->count(),
            'resolved' => Complaint::where('status', 'resolved')->count(),
            'closed' => Complaint::where('status', 'closed')->count(),
        ];

        // Get recent high priority complaints
        $highPriorityComplaints = Complaint::where('priority', 'urgent')
            ->orWhere('priority', 'high')
            ->latest()
            ->limit(5)
            ->with('user')
            ->get();

        // Get all complaints for map
        $complaints = Complaint::with('user')->latest()->get();

        return view('management.dashboard', compact('stats', 'highPriorityComplaints', 'complaints'));
    }
}
