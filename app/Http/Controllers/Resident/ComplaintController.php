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
            'category' => 'required|in:maintenance,noise,safety,cleanliness,other',
            'description' => 'required|string|max:1000',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480',
        ]);

        // Send the complaint description to the Python AI API
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(5)
                ->post('http://127.0.0.1:8001/predict', [
                    'description' => $validated['description'],
                ]);

            if ($response->successful()) {
                $validated['priority'] = $response->json('priority');
            } else {
                $validated['priority'] = 'medium';
            }
        } catch (\Exception $e) {
            // Use medium if the AI server is unavailable
            $validated['priority'] = 'medium';
        }

        // Set the initial complaint status.
        $validated['status'] = 'pending';

        // Store the uploaded file.
        $image = $request->file('image');

        if ($image) {
            $validated['image'] = $image->store('complaint-images', 'public');
        }

        // Save the complaint for the authenticated resident.
        $complaint = auth()->user()->complaints()->create($validated);

        return redirect()
            ->route('resident.submit-complaint')
            ->with('success', 'Complaint submitted successfully!');
    }

    /**
     * Display a listing of the resident's complaints.
     */
    public function index(Request $request)
    {
        $query = auth()->user()->complaints()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $complaints = $query->paginate(10)->appends($request->query());

        return view('resident.complaints', compact('complaints'));
    }

}
