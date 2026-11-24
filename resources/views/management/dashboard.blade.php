@extends('layouts.management')

@section('content')
<div class="card app-card">
    <div class="card-body">
        <h2 class="h4 mb-3 text-center">Management Dashboard</h2>
        <p class="mb-0 text-center">Welcome, {{ auth()->user()->name ?? 'User' }}.</p>
    </div>
</div>

<div class="row g-4 mt-2">

    <!-- Total Complaints -->
    <div class="col-md-3">
        <div class="card app-card h-100">
            <div class="card-body">
                <h6 class="text-muted">Total Complaints</h6>
                <h2 class="fw-bold text-primary">{{ $stats['total'] ?? 0 }}</h2>
                <small class="text-muted">All submitted complaints</small>
            </div>
        </div>
    </div>

    <!-- Pending Complaints -->
    <div class="col-md-3">
        <div class="card app-card h-100">
            <div class="card-body">
                <h6 class="text-muted">Pending Complaints</h6>
                <h2 class="fw-bold text-warning">{{ $stats['pending'] ?? 0 }}</h2>
                <small class="text-muted">Waiting for review</small>
            </div>
        </div>
    </div>

    <!-- In Progress -->
    <div class="col-md-3">
        <div class="card app-card h-100">
            <div class="card-body">
                <h6 class="text-muted">In Progress</h6>
                <h3 class="fw-bold text-primary">{{ $stats['in_progress'] ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <!-- Resolved Complaints -->
    <div class="col-md-3">
        <div class="card app-card h-100">
            <div class="card-body">
                <h6 class="text-muted">Resolved Complaints</h6>
                <h2 class="fw-bold text-success">{{ $stats['resolved'] ?? 0 }}</h2>
                <small class="text-muted">Successfully completed</small>
            </div>
        </div>
    </div>

</div>

<!-- Unique Value Proposition (UVP) Section -->
<div class="row g-4 mt-2">

        <!-- AI Priority Complaints -->
        <div class="col-md-6">
            <div class="card app-card h-100">
                <div class="">
                    <div class="card-header">
                        Recent High Priority Complaints
                    </div>

                    <div class="card-body" style="height:320px; border-radius:10px;">
                        <div class="table-responsive">
                        <table class="table table-dark table-borderless align-middle">
                            <thead>
                                <tr>
                                    <th>No. of Complaint</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($highPriorityComplaints as $complaint)
                                    <tr>
                                        <td>{{ $complaint->id }}</td>
                                        <td>{{ ucfirst($complaint->category) }}</td>
                                        <td>
                                            <span class="badge bg-danger">
                                                {{ ucfirst($complaint->priority) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">
                                            No high priority complaints
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        <!-- GIS Complaint Map -->
        <div class="col-md-6 ">

            <div class="card app-card h-100">

                <div class="card-header">
                    <h5 class="mb-0 text-white">
                        <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                        Complaint Location Map
                    </h5>
                </div>

                <div class="card-body">
                    <h6 class="d-flex align-items-center gap-3 flex-wrap">
                        LEGEND:

                        <span>
                            <i class="bi bi-circle-fill text-danger"></i> High
                        </span>

                        <span>
                            <i class="bi bi-circle-fill text-warning"></i> Medium
                        </span>

                        <span>
                            <i class="bi bi-circle-fill" style="color: #198754;"></i> Low
                        </span>
                    </h6>

                    <div id="complaintMap"
                        style="height:290px; border-radius:10px;">
                    </div>

                </div>

            </div>

        </div>
        
</div>

<!-- Complaint Analytics -->
<div class="row g-4 mt-2">

    <div class="col-12">

        <div class="card app-card analytics-card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h5 class="mb-0 text-white">
                    <i class="bi bi-graph-up-arrow text-primary me-2"></i>
                    Complaint Analytics
                </h5>

                <div>
                    <button class="btn btn-sm btn-outline-secondary text-white">
                        This Month
                    </button>
                </div>

            </div>

            <div class="card-body">

                <canvas id="analyticsChart"></canvas>

            </div>

        </div>

    </div>

</div>


@push('scripts')

<script>

const map = L.map('complaintMap').setView([16.4023, 120.5960], 13);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Add complaint markers to the map
const complaints = {!! json_encode(
    $complaints
        ->filter(function ($c) {
            return strtolower($c->status) !== 'resolved';
        })
        ->map(function ($c) {
            return [
                'lat' => $c->latitude,
                'lng' => $c->longitude,
                'id' => $c->id,
                'category' => $c->category,
                'priority' => $c->priority
            ];
        })
        ->values()
) !!};

complaints.forEach(complaint => {
    let color;

    if (complaint.priority === 'high') {
        color = '#dc3545'; // Red
    } else if (complaint.priority === 'medium') {
        color = '#fd7e14'; // Orange
    } else if (complaint.priority === 'low') {
        color = '#198754'; // Green
    } else {
        color = '#6c757d'; // Gray if priority is unknown
    }

    L.circleMarker([complaint.lat, complaint.lng], {
        radius: 7,
        fillColor: color,
        color: '#fff',
        weight: 2,
        opacity: 1,
        fillOpacity: 0.9
    }).bindPopup(`
        <strong>Complaint #${complaint.id}</strong><br>
        Category: ${complaint.category}<br>
        Priority: <strong style="color: ${color};">
            ${complaint.priority ? complaint.priority.toUpperCase() : 'UNKNOWN'}
        </strong>
    `).addTo(map);
});

setTimeout(() => {
    map.invalidateSize();
}, 100);

// charts
new Chart(document.getElementById('totalChart'), {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun'],
        datasets: [{
            data: [15,22,30,40,55,70],
            borderColor: '#0d6efd',
            borderWidth: 2,
            fill: false,
            pointRadius: 0,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                display: false
            },
            y: {
                display: false
            }
        }
    }
});

new Chart(document.getElementById('analyticsChart'), {

    type: 'line',

    data: {

        labels: [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun'
        ],

        datasets: [{

            label: 'Complaints',

            data: [
                18,
                26,
                21,
                35,
                29,
                45
            ],

            borderColor: '#4F7DFF',

            backgroundColor: 'rgba(79,125,255,.15)',

            fill: true,

            tension: .45,

            pointRadius: 4,

            pointBackgroundColor: '#4F7DFF',

            borderWidth: 3

        }]

    },

    options: {

        responsive: true,

        maintainAspectRatio: false,

        plugins: {

            legend: {
                display: false
            }

        },

        scales: {

            x: {

                ticks: {
                    color: '#94A3B8'
                },

                grid: {
                    color: '#24324A'
                }

            },

            y: {

                beginAtZero: true,

                ticks: {
                    color: '#94A3B8'
                },

                grid: {
                    color: '#24324A'
                }

            }

        }

    }

});

</script>

@endpush


@endsection
