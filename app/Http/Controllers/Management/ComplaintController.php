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
        $query = Complaint::with([
            'user',
            'statusUpdatedBy'
        ])->where('status', '!=', 'resolved');


        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Sort by priority: High → Medium → Low
        $complaints = $query
            ->orderByRaw("
                CASE
                    WHEN LOWER(priority) = 'high' THEN 1
                    WHEN LOWER(priority) = 'medium' THEN 2
                    WHEN LOWER(priority) = 'low' THEN 3
                    ELSE 4
                END
            ")
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->query());

        // Get statistics
        $stats = [
            'total' => Complaint::count(),
            'pending' => Complaint::where('status', 'pending')->count(),
            'in_progress' => Complaint::where('status', 'in_progress')->count(),
            'resolved' => Complaint::where('status', 'resolved')->count(),
        ];

        return view(
            'management.complaints',
            compact('complaints', 'stats')
        );
    }



    //  * Display a listing of archived complaints.
    public function archived()
    {
        $query = Complaint::with([
            'user',
            'statusUpdatedBy'
        ])->where('status', 'resolved');

        $totalArchived = (clone $query)->count();

        $resolvedCount = (clone $query)->count();

        $archivedThisMonth = (clone $query)
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();

        $complaints = (clone $query)
            ->orderByDesc('updated_at')
            ->paginate(15);

        // Statistics for the Archived page
        $stats = [
            'resolved' => Complaint::where('status', 'resolved')->count(),
        ];

        return view('management.archived', compact(
            'complaints',
            'totalArchived',
            'resolvedCount',
            'archivedThisMonth',
            'stats'
        ));
    }




    /**
     * Update the status of a complaint.
     */
    
    public function updateStatus(
        Request $request,
        Complaint $complaint
    ) {
        /*
        |--------------------------------------------------------------------------
        | LOCK RESOLVED COMPLAINTS
        |--------------------------------------------------------------------------
        */

        if ($complaint->status === 'resolved') {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'This complaint has already been resolved and can no longer be updated.'
                );
        }

        $validated = $request->validate([
            'status' => [
                'required',
                'string',
                'in:pending,in_progress,resolved'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | UPDATE STATUS AND RECORD WHO UPDATED IT
        |--------------------------------------------------------------------------
        */

        $complaint->update([
            'status' => $validated['status'],
            'status_updated_by' => auth()->id(),
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Complaint status updated successfully!'
            );
    }



    /**
     * Get complaints by category.
     */
    public function filterByCategory($category)
    {
        $complaints = Complaint::where(
                'category',
                $category
            )
            ->with([
                'user',
                'statusUpdatedBy'
            ])
            ->latest()
            ->paginate(15);

        return view(
            'management.complaints',
            compact('complaints')
        );
    }


    /**
     * Get complaints by priority.
     */
    public function filterByPriority($priority)
    {
        $complaints = Complaint::where(
                'priority',
                $priority
            )
            ->with([
                'user',
                'statusUpdatedBy'
            ])
            ->latest()
            ->paginate(15);

        return view(
            'management.complaints',
            compact('complaints')
        );
    }


    /**
     * Get complaints by status.
     */
    public function filterByStatus($status)
    {
        $complaints = Complaint::where(
                'status',
                $status
            )
            ->with([
                'user',
                'statusUpdatedBy'
            ])
            ->latest()
            ->paginate(15);

        return view(
            'management.complaints',
            compact('complaints')
        );
    }
}