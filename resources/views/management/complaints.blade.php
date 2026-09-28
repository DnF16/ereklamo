@extends('layouts.management')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Complaint Management</h3>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">

        <div class="col-md-3 mb-3">
            <div class="card app-card h-100">
                <div class="card-body">
                    <h6 class="text-muted">Total Complaints</h6>
                    <h3 class="fw-bold text-primary">{{ $stats['total'] ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card app-card h-100">
                <div class="card-body">
                    <h6 class="text-muted">Pending</h6>
                    <h3 class="fw-bold text-warning">{{ $stats['pending'] ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card app-card h-100">
                <div class="card-body">
                    <h6 class="text-muted">In Progress</h6>
                    <h3 class="fw-bold text-primary">{{ $stats['in_progress'] ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card app-card h-100">
                <div class="card-body">
                    <h6 class="text-muted">Resolved</h6>
                    <h3 class="fw-bold text-success">{{ $stats['resolved'] ?? 0 }}</h3>
                </div>
            </div>
        </div>

    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.complaints') }}" class="card app-card mb-4">
        <div class="card-body">

            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Status</label>
                    <select class="form-select" name="status">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Priority</label>
                    <select class="form-select" name="priority">
                        <option value="">All Priorities</option>
                        <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    @if (request('status') || request('priority'))
                        <a href="{{ route('admin.complaints') }}" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </div>

        </div>
    </form>

    <!-- Complaint Table -->
    <div class="card app-card">

        <div class="card-header">
            <h5 class="mb-0">Submitted Complaints</h5>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-dark align-middle mb-0">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Resident</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Date Submitted</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($complaints as $complaint)
                            <tr>
                                <td>{{ $complaint->id }}</td>
                                <td>{{ $complaint->user->name }}</td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($complaint->category) }}</span>
                                </td>
                                <td>
                                    <small>{{ Str::limit($complaint->description, 40) }}</small>
                                </td>
                                <td>
                                    @if ($complaint->priority === 'urgent')
                                        <span class="badge bg-danger">{{ ucfirst($complaint->priority) }}</span>
                                    @elseif ($complaint->priority === 'high')
                                        <span class="badge bg-danger">{{ ucfirst($complaint->priority) }}</span>
                                    @elseif ($complaint->priority === 'medium')
                                        <span class="badge bg-warning text-dark">{{ ucfirst($complaint->priority) }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($complaint->priority) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($complaint->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif ($complaint->status === 'in_progress')
                                        <span class="badge bg-primary">In Progress</span>
                                    @elseif ($complaint->status === 'resolved')
                                        <span class="badge bg-success">Resolved</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($complaint->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $complaint->created_at->format('M d, Y') }}</small>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#complaintDetailsModal-{{ $complaint->id }}">
                                        View
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    No complaints available.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $complaints->links('pagination::bootstrap-5') }}
    </div>

</div>

@foreach ($complaints as $complaint)
    <div class="modal fade" id="complaintDetailsModal-{{ $complaint->id }}" tabindex="-1" aria-labelledby="complaintDetailsModalLabel-{{ $complaint->id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-bold" id="complaintDetailsModalLabel-{{ $complaint->id }}">Complaint #{{ $complaint->id }}</h5>
                        <small class="text-muted">Review and manage complaint details</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="card app-card mb-3">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Complaint Summary</h6>
                                    @if ($complaint->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif ($complaint->status === 'in_progress')
                                        <span class="badge bg-primary">In Progress</span>
                                    @elseif ($complaint->status === 'resolved')
                                        <span class="badge bg-success">Resolved</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($complaint->status) }}</span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <h6 class="text-muted small fw-semibold">Category</h6>
                                            <p class="mb-0"><span class="badge bg-info">{{ ucfirst($complaint->category) }}</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-muted small fw-semibold">Priority</h6>
                                            <p class="mb-0">
                                                @if ($complaint->priority === 'urgent')
                                                    <span class="badge bg-danger">{{ ucfirst($complaint->priority) }}</span>
                                                @elseif ($complaint->priority === 'high')
                                                    <span class="badge bg-warning text-dark">{{ ucfirst($complaint->priority) }}</span>
                                                @elseif ($complaint->priority === 'medium')
                                                    <span class="badge bg-warning text-dark">{{ ucfirst($complaint->priority) }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($complaint->priority) }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <h6 class="text-muted small fw-semibold">Location</h6>
                                        <!-- <p class="mb-0 text-muted small">Latitude: {{ $complaint->latitude }}, Longitude: {{ $complaint->longitude }}</p> -->
                                         <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <p class="mb-1 text-muted small">
                                                    Latitude: {{ $complaint->latitude }}
                                                </p>
                                                <p class="mb-0 text-muted small">
                                                    Longitude: {{ $complaint->longitude }}
                                                </p>
                                            </div>

                                            @if ($complaint->latitude && $complaint->longitude)
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-outline-primary"
                                                    data-location-modal="#complaintLocationModal-{{ $complaint->id }}"
                                                >
                                                    <i class="bi bi-geo-alt me-1"></i>
                                                    View Location
                                                </button>
                                            @else
                                                <span class="text-muted small">No location provided</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <h6 class="text-muted small fw-semibold">Description</h6>
                                        <p class="mb-0">{{ $complaint->description }}</p>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-muted small fw-semibold">Date Submitted</h6>
                                            <p class="mb-0">{{ $complaint->created_at->format('F d, Y \a\t g:i A') }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-muted small fw-semibold">Last Updated</h6>
                                            <p class="mb-0">{{ $complaint->updated_at->format('F d, Y \a\t g:i A') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card app-card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Update Status</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.complaint-status-update', $complaint->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <div class="mb-3">
                                            <label for="statusSelect-{{ $complaint->id }}" class="form-label fw-semibold small">New Status</label>
                                            <select class="form-select" id="statusSelect-{{ $complaint->id }}" name="status" required>
                                                <option value="pending" {{ $complaint->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="in_progress" {{ $complaint->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                <option value="resolved" {{ $complaint->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                                <option value="closed" {{ $complaint->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                            </select>
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="bi bi-check-circle me-2"></i>Update Status
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="card app-card">
                                <div class="card-header">
                                    <h6 class="mb-0">Resident Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-circle bg-primary me-3">
                                            {{ Str::substr($complaint->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 small fw-semibold">{{ $complaint->user->name }}</h6>
                                            <small class="text-muted">{{ $complaint->user->email }}</small>
                                        </div>
                                    </div>
                                    <hr>
                                    <p class="mb-0 small text-muted">
                                        <strong>Role:</strong> {{ ucfirst($complaint->user->role) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

        <!-- Location Modal -->
        @if ($complaint->latitude && $complaint->longitude)

        <div
            class="modal fade"
            id="complaintLocationModal-{{ $complaint->id }}"
            tabindex="-1"
            aria-labelledby="complaintLocationModalLabel-{{ $complaint->id }}"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">

                    <div class="modal-header">
                        <div>
                            <h5
                                class="modal-title fw-bold"
                                id="complaintLocationModalLabel-{{ $complaint->id }}"
                            >
                                Complaint #{{ $complaint->id }} Location
                            </h5>

                            <small class="text-muted">
                                Pinned complaint location
                            </small>
                        </div>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>

                    <div class="modal-body p-0">

                        <div
                            id="complaint-map-{{ $complaint->id }}"
                            style="height: 500px; width: 100%;"
                        ></div>

                    </div>

                    <div class="modal-footer">

                        <span class="text-muted small me-auto">
                            <i class="bi bi-geo-alt me-1"></i>
                            {{ $complaint->latitude }},
                            {{ $complaint->longitude }}
                        </span>

                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Close
                        </button>

                    </div>

                </div>
            </div>
        </div>

    @endif
@endforeach

<style>
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('[data-location-modal]').forEach(function (button) {
        button.addEventListener('click', function () {
            const locationModal = document.querySelector(button.dataset.locationModal);
            const modalInstance = bootstrap.Modal.getOrCreateInstance(locationModal);

            locationModal.style.zIndex = '1060';
            locationModal.addEventListener('shown.bs.modal', function () {
                const backdrops = document.querySelectorAll('.modal-backdrop');
                const backdrop = backdrops[backdrops.length - 1];

                if (backdrop) {
                    backdrop.style.zIndex = '1055';
                }
            }, { once: true });

            modalInstance.show();
        });
    });

    @foreach ($complaints as $complaint)

        @if ($complaint->latitude && $complaint->longitude)

            const locationModal{{ $complaint->id }} =
                document.getElementById(
                    'complaintLocationModal-{{ $complaint->id }}'
                );

            let complaintMap{{ $complaint->id }} = null;

            locationModal{{ $complaint->id }}.addEventListener(
                'shown.bs.modal',
                function () {

                    const latitude = {{ $complaint->latitude }};
                    const longitude = {{ $complaint->longitude }};

                    if (!complaintMap{{ $complaint->id }}) {

                        complaintMap{{ $complaint->id }} = L.map(
                            'complaint-map-{{ $complaint->id }}'
                        ).setView(
                            [latitude, longitude],
                            18
                        );

                        L.tileLayer(
                            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                            {
                                attribution: '&copy; OpenStreetMap contributors'
                            }
                        ).addTo(
                            complaintMap{{ $complaint->id }}
                        );

                        let priorityColor{{ $complaint->id }};

                        @if ($complaint->priority === 'high')
                            priorityColor{{ $complaint->id }} = '#dc3545'; // Red
                        @elseif ($complaint->priority === 'medium')
                            priorityColor{{ $complaint->id }} = '#fd7e14'; // Orange
                        @elseif ($complaint->priority === 'low')
                            priorityColor{{ $complaint->id }} = '#198754'; // Green
                        @else
                            priorityColor{{ $complaint->id }} = '#6c757d'; // Gray
                        @endif

                        L.circleMarker(
                            [latitude, longitude],
                            {
                                radius: 10,
                                fillColor: priorityColor{{ $complaint->id }},
                                color: '#fff',
                                weight: 3,
                                opacity: 1,
                                fillOpacity: 0.9
                            }
                        )
                        .addTo(
                            complaintMap{{ $complaint->id }}
                        )
                        .bindPopup(
                            '<strong>Complaint #{{ $complaint->id }}</strong><br>' +
                            '{{ ucfirst($complaint->priority) }} Priority'
                        )
                        .openPopup();

                    } else {

                        complaintMap{{ $complaint->id }}.invalidateSize();

                        complaintMap{{ $complaint->id }}.setView(
                            [latitude, longitude],
                            18
                        );
                    }

                }
            );

        @endif

    @endforeach

});
</script>
@endsection