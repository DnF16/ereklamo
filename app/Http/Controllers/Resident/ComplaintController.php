<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    /**
     * Show the form for creating a new complaint.
     */
    public function create()
    {
        $complaints = auth()->user()->complaints()->latest()->get();
        return view('resident.submit-complaint', compact('complaints'));
    }

    /**
     * Store a newly created complaint in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:1000',
            'category' => 'required|string|in:maintenance,noise,safety,cleanliness,other',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        // Set default priority - staff will set actual priority
        $validated['priority'] = 'medium';

        $complaint = auth()->user()->complaints()->create($validated);

        return redirect()->route('resident.complaints')
            ->with('success', 'Complaint submitted successfully!');
    }

    /**
     * Display a listing of the resident's complaints.
     */
    public function index()
    {
        $complaints = auth()->user()->complaints()
            ->latest()
            ->paginate(10);

        return view('resident.complaints', compact('complaints'));
    }

}
