<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    /**
     * Display a listing of all complaints for admin dashboard.
     */
    public function index(Request $request)
    {
        $query = Complaint::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $complaints = $query->paginate(15)->appends($request->query());

        // Get statistics
        $stats = [
            'total' => Complaint::count(),
            'pending' => Complaint::where('status', 'pending')->count(),
            'in_progress' => Complaint::where('status', 'in_progress')->count(),
            'resolved' => Complaint::where('status', 'resolved')->count(),
            'closed' => Complaint::where('status', 'closed')->count(),
        ];

        return view('management.complaints', compact('complaints', 'stats'));
    }

    /**
     * Update the status of a complaint.
     */
    public function updateStatus(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,in_progress,resolved,closed',
        ]);

        $complaint->update($validated);

        return redirect()->back()
            ->with('success', 'Complaint status updated successfully!');
    }

    /**
     * Get complaints by category.
     */
    public function filterByCategory($category)
    {
        $complaints = Complaint::where('category', $category)
            ->with('user')
            ->latest()
            ->paginate(15);

        return view('management.complaints', compact('complaints'));
    }

    /**
     * Get complaints by priority.
     */
    public function filterByPriority($priority)
    {
        $complaints = Complaint::where('priority', $priority)
            ->with('user')
            ->latest()
            ->paginate(15);

        return view('management.complaints', compact('complaints'));
    }

    /**
     * Get complaints by status.
     */
    public function filterByStatus($status)
    {
        $complaints = Complaint::where('status', $status)
            ->with('user')
            ->latest()
            ->paginate(15);

        return view('management.complaints', compact('complaints'));
    }
}
