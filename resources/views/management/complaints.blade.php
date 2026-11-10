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
                        <!-- <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option> -->
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
                                        <div class="col-md-4">
                                            <h6 class="text-muted small fw-semibold">Category</h6>
                                            <p class="mb-0"><span class="badge bg-info">{{ ucfirst($complaint->category) }}</span></p>
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="text-muted small fw-semibold">Priority</h6>
                                            <p class="mb-0">
                                                @if ($complaint->priority === 'urgent')
                                                    <span class="badge bg-danger">{{ ucfirst($complaint->priority) }}</span>
                                                @elseif ($complaint->priority === 'high')
                                                    <span class="badge bg-danger">{{ ucfirst($complaint->priority) }}</span>
                                                @elseif ($complaint->priority === 'medium')
                                                    <span class="badge bg-warning text-dark">{{ ucfirst($complaint->priority) }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($complaint->priority) }}</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="text-muted small fw-semibold">Updated by:</h6>
                                            <p class="mb-0">{{ $complaint->statusUpdatedBy->name ?? 'N/A' }}</p>
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

                                    <!-- Evidence Image -->
                                    <div class="mb-4">
                                        <div class="card-header">
                                            <h6 class="mb-0">Evidence Image</h6>
                                        </div>

                                        <div class="card-body">
                                            @if ($complaint->image)
                                                <img
                                                    src="{{ asset('storage/' . $complaint->image) }}"
                                                    alt="Resident's complaint evidence"
                                                    class="img-fluid rounded evidence-thumbnail"
                                                    style="max-width: 50%; max-height: 130px; object-fit: contain; cursor: zoom-in;"
                                                    title="Click to enlarge"
                                                >
                                                <small class="d-block text-muted mt-1">
                                                    Click image to enlarge
                                                </small>
                                            @else
                                                <p class="text-muted mb-0">
                                                    No evidence image was uploaded for this complaint.
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <h6 class="text-muted small fw-semibold">Description</h6>
                                        <p class="mb-0">{{ $complaint->description }}</p>
                                    </div>

                                    <hr>

                                    <div class="row g-3">
                                        <!-- Date Submitted -->
                                        <div class="col-md-6">

                                            <h6 class="text-muted small fw-semibold">
                                                Date Submitted
                                            </h6>

                                            <p class="mb-0">
                                                {{ $complaint->created_at->format('F d, Y \a\t g:i A') }}
                                            </p>
                                        </div>

                                        <!-- Last Updated -->
                                        <div class="col-md-6">
                                            <h6 class="text-muted small fw-semibold">
                                                Last Updated
                                            </h6>

                                            <p class="mb-0">
                                                {{ $complaint->updated_at->format('F d, Y \a\t g:i A') }}
                                            </p>
                                        </div>

                                        <!-- Status Updated By -->
                                        <div class="col-md-6">
                                            <h6 class="text-muted small fw-semibold">
                                                Status Updated By
                                            </h6>

                                            @if ($complaint->statusUpdatedBy)
                                                <div class="d-flex align-items-center">

                                                    <div class="avatar-circle bg-primary me-2">
                                                        {{ Str::substr($complaint->statusUpdatedBy->name, 0, 1) }}
                                                    </div>

                                                    <div>

                                                        <p class="mb-0 fw-semibold">
                                                            {{ $complaint->statusUpdatedBy->name }}
                                                        </p>

                                                        <small class="text-muted">
                                                            {{ ucfirst($complaint->statusUpdatedBy->role) }}
                                                        </small>

                                                    </div>
                                                </div>
                                            @else
                                                <p class="mb-0 text-muted">
                                                    Not updated yet
                                                </p>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                                @if (strtolower($complaint->status) === 'resolved')

                                    {{-- LOCKED: RESOLVED COMPLAINT --}}
                                    <div class="card app-card mb-3">
                                        <div class="card-header">
                                            <h6 class="mb-0">Complaint Status</h6>
                                        </div>

                                        <div class="card-body">
                                            <span class="badge bg-success">
                                                <i class="bi bi-lock-fill me-1"></i>
                                                Resolved — Locked
                                            </span>

                                            <p class="text-muted small mt-3 mb-0">
                                                This complaint has been resolved.
                                                Its status can no longer be updated.
                                            </p>
                                        </div>
                                    </div>

                                @else

                                    {{-- UPDATE STATUS: UNRESOLVED COMPLAINTS --}}
                                    <div class="card app-card mb-3">
                                        <div class="card-header">
                                            <h6 class="mb-0">Update Status</h6>
                                        </div>

                                        <div class="card-body">
                                            <form
                                                action="{{ route('admin.complaint-status-update', $complaint->id) }}"
                                                method="POST"
                                                data-status-update-form
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <div class="mb-3">
                                                    <label
                                                        for="statusSelect-{{ $complaint->id }}"
                                                        class="form-label fw-semibold small"
                                                    >
                                                        New Status
                                                    </label>

                                                    <select
                                                        class="form-select"
                                                        id="statusSelect-{{ $complaint->id }}"
                                                        name="status"
                                                        data-current-status="{{ $complaint->status }}"
                                                        required
                                                    >
                                                        <option value="pending"
                                                            {{ $complaint->status === 'pending' ? 'selected' : '' }}>
                                                            Pending
                                                        </option>

                                                        <option value="in_progress"
                                                            {{ $complaint->status === 'in_progress' ? 'selected' : '' }}>
                                                            In Progress
                                                        </option>

                                                        <option value="resolved"
                                                            {{ $complaint->status === 'resolved' ? 'selected' : '' }}>
                                                            Resolved
                                                        </option>
                                                    </select>
                                                </div>

                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="bi bi-check-circle me-2"></i>
                                                    Update Status
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                @endif
                            
                            
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

<div class="modal fade" id="statusUpdateConfirmationModal" tabindex="-1" aria-labelledby="statusUpdateConfirmationLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusUpdateConfirmationLabel">Confirm Status Update</h5>
            </div>
            <div class="modal-body">
                Change status from <strong id="currentComplaintStatus"></strong> to <strong id="newComplaintStatus"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelStatusUpdate">No</button>
                <button type="button" class="btn btn-primary" id="confirmStatusUpdate">Yes, update</button>
            </div>
        </div>
    </div>
</div>


<!-- Evidence Image Enlargement Modal -->
<div
    class="evidence-lightbox"
    id="evidenceImageModal"
    role="dialog"
    aria-modal="true"
    aria-label="Evidence image"
    aria-hidden="true"
>
    <button
        type="button"
        class="evidence-lightbox-close"
        aria-label="Close enlarged evidence image"
    >&times;</button>

    <img id="fullEvidenceImage" alt="Enlarged complaint evidence">
</div>


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

    .evidence-lightbox {
    position: fixed;
    inset: 0;
    z-index: 2000;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 3rem;
    background: rgba(0, 0, 0, 0.90);
    cursor: zoom-out;
    backdrop-filter: blur(4px);
    }

    .evidence-lightbox.is-open {
        display: flex;
    }

    .evidence-lightbox img {
        display: block;
        max-width: 95vw;
        max-height: 90vh;
        width: auto;
        height: auto;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 8px 40px rgba(0, 0, 0, 0.4);
        cursor: default;
    }

    .evidence-lightbox-close {
        position: absolute;
        top: 1rem;
        right: 1.5rem;
        z-index: 1;
        border: 0;
        background: transparent;
        color: white;
        font-size: 2.5rem;
        line-height: 1;
        cursor: pointer;
    }

    .evidence-thumbnail {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .evidence-thumbnail:hover {
        transform: scale(1.03);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    @media (max-width: 576px) {
        .evidence-lightbox {
            padding: 1rem;
        }

        .evidence-lightbox img {
            max-width: 96vw;
            max-height: 85vh;
        }

        .evidence-lightbox-close {
            top: 0.5rem;
            right: 0.75rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const statusConfirmationModal = document.getElementById('statusUpdateConfirmationModal');
    const currentStatusLabel = document.getElementById('currentComplaintStatus');
    const newStatusLabel = document.getElementById('newComplaintStatus');
    let pendingStatusForm = null;
    let pendingStatusSelect = null;
    let confirmedStatusForm = null;

    document.querySelectorAll('[data-status-update-form]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            const statusSelect = form.querySelector('[data-current-status]');

            if (confirmedStatusForm === form) {
                confirmedStatusForm = null;
                return;
            }

            if (!statusSelect || statusSelect.value === statusSelect.dataset.currentStatus) {
                event.preventDefault();
                return;
            }

            event.preventDefault();
            pendingStatusForm = form;
            pendingStatusSelect = statusSelect;
            const currentStatusOption = Array.from(statusSelect.options).find(function (option) {
                return option.value === statusSelect.dataset.currentStatus;
            });
            currentStatusLabel.textContent = currentStatusOption ? currentStatusOption.text : '';
            newStatusLabel.textContent = statusSelect.options[statusSelect.selectedIndex].text;

            statusConfirmationModal.style.zIndex = '1060';
            statusConfirmationModal.addEventListener('shown.bs.modal', function () {
                const backdrops = document.querySelectorAll('.modal-backdrop');
                const backdrop = backdrops[backdrops.length - 1];

                if (backdrop) {
                    backdrop.style.zIndex = '1055';
                }
            }, { once: true });

            bootstrap.Modal.getOrCreateInstance(statusConfirmationModal).show();
        });
    });

    document.getElementById('cancelStatusUpdate').addEventListener('click', function () {
        if (pendingStatusSelect) {
            pendingStatusSelect.value = pendingStatusSelect.dataset.currentStatus;
        }

        pendingStatusForm = null;
        pendingStatusSelect = null;
        bootstrap.Modal.getOrCreateInstance(statusConfirmationModal).hide();
    });

    document.getElementById('confirmStatusUpdate').addEventListener('click', function () {
        if (pendingStatusForm) {
            const form = pendingStatusForm;
            confirmedStatusForm = form;
            pendingStatusForm = null;
            pendingStatusSelect = null;
            form.requestSubmit();
        }

        bootstrap.Modal.getOrCreateInstance(statusConfirmationModal).hide();
    });

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

    // Click evidence image to view it larger
    const evidenceModal = document.getElementById('evidenceImageModal');
    const fullEvidenceImage = document.getElementById('fullEvidenceImage');
    const closeEvidenceImage = evidenceModal
        ? evidenceModal.querySelector('.evidence-lightbox-close')
        : null;

    let evidenceImageTrigger = null;

    if (evidenceModal && fullEvidenceImage && closeEvidenceImage) {

        document.querySelectorAll('.evidence-thumbnail').forEach(function (image) {
            image.addEventListener('click', function () {
                evidenceImageTrigger = image;

                fullEvidenceImage.src = image.src;

                evidenceModal.classList.add('is-open');
                evidenceModal.setAttribute('aria-hidden', 'false');

                document.body.style.overflow = 'hidden';

                closeEvidenceImage.focus();
            });
        });

        function hideEvidenceImage() {
            evidenceModal.classList.remove('is-open');
            evidenceModal.setAttribute('aria-hidden', 'true');

            fullEvidenceImage.src = '';
            document.body.style.overflow = '';

            if (evidenceImageTrigger) {
                evidenceImageTrigger.focus();
                evidenceImageTrigger = null;
            }
        }

        closeEvidenceImage.addEventListener('click', hideEvidenceImage);

        evidenceModal.addEventListener('click', function (event) {
            if (event.target === evidenceModal) {
                hideEvidenceImage();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (
                event.key === 'Escape' &&
                evidenceModal.classList.contains('is-open')
            ) {
                hideEvidenceImage();
            }
        });
    }

});
</script>
@endsection