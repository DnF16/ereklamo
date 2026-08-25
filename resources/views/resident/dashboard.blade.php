@extends('layouts.resident')

@section('content')

<div class="container-fluid">

    <!-- Welcome Section -->
    <div class="card app-card mb-4">
        <div class="card-body">
            <h3 class="fw-bold">
                Welcome, {{ auth()->user()->name }}!
            </h3>

            <p class="text-muted mb-4">
                Welcome to <strong>e-REKLAMO</strong>. Submit and monitor your
                complaints online without visiting the barangay office.
            </p>

            
        </div>
    </div>

    <!-- Barangay Announcements -->
    <div class="card app-card mb-4">

        <div class="card-header">
            <h5 class="mb-0">Barangay Announcements</h5>
        </div>

        <div class="card-body">

            <ul class="mb-0">
                <li>Barangay Cleanup Drive - July 15, 2026</li>
                <li>Water Interruption - July 18, 2026</li>
                <li>Barangay Office is open from 8:00 AM to 5:00 PM.</li>
            </ul>

        </div>

    </div>

    <!-- How e-REKLAMO Works -->
    <div class="card app-card mb-4">

        <div class="card-header">
            <h5 class="mb-0">How e-REKLAMO Works</h5>
        </div>

        <div class="card-body">

            <div class="row text-center">

                <div class="col-md-3 mb-3">
                    <h1>📝</h1>
                    <h6 class="fw-bold">Submit</h6>
                    <p class="text-muted">
                        Fill out the complaint form and provide the necessary details.
                    </p>
                </div>

                <div class="col-md-3 mb-3">
                    <h1>🤖</h1>
                    <h6 class="fw-bold">AI Analysis</h6>
                    <p class="text-muted">
                        The system uses NLP and Machine Learning to determine the complaint's priority.
                    </p>
                </div>

                <div class="col-md-3 mb-3">
                    <h1>👨‍💼</h1>
                    <h6 class="fw-bold">Review</h6>
                    <p class="text-muted">
                        Barangay staff reviews and processes your complaint.
                    </p>
                </div>

                <div class="col-md-3 mb-3">
                    <h1>📍</h1>
                    <h6 class="fw-bold">Track</h6>
                    <p class="text-muted">
                        Monitor your complaint's progress through the My Complaints page.
                    </p>
                </div>

            </div>

        </div>

    </div>

</div>


@endsection
