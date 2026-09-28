@extends('layouts.resident')

@section('content')
<div class="container-fluid py-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h2 class="fw-bold mb-1">My Complaints</h2>
            <p class="text-muted small mb-0">Track and manage your submitted complaints.</p>
        </div>
        <a href="{{ route('resident.submit-complaint') }}" class="btn btn-primary shadow-sm px-4 py-2">
            <i class="bi bi-plus-circle-fill me-2"></i>File New Complaint
        </a>
    </div>

    <form method="GET" action="{{ route('resident.complaints') }}" class="card app-card mb-4">
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
                        <a href="{{ route('resident.complaints') }}" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </div>
        </div>
    </form>

    @if ($complaints->count() > 0)
        <!-- Complaints Table -->
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
                                <th>Category</th>
                                <th>Description</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Date Submitted</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($complaints as $complaint)
                                <tr>
                                    <td>{{ $complaint->id }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($complaint->category) }}</span>
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($complaint->description, 50) }}</small>
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @foreach ($complaints as $complaint)
            <div class="modal fade" id="complaintDetailsModal-{{ $complaint->id }}" tabindex="-1" aria-labelledby="complaintDetailsModalLabel-{{ $complaint->id }}" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header">
                            <div>
                                <h5 class="modal-title fw-bold" id="complaintDetailsModalLabel-{{ $complaint->id }}">Complaint #{{ $complaint->id }}</h5>
                                <small class="text-muted">Complaint details</small>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="row g-4">
                                <div class="col-lg-8">
                                    <div class="card app-card mb-3">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">Status</h6>
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
                                                <p class="mb-0 text-muted small">Latitude: {{ $complaint->latitude }}, Longitude: {{ $complaint->longitude }}</p>
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
                                    <div class="card app-card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Status Timeline</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="timeline">
                                                <div class="timeline-item">
                                                    <div class="timeline-marker bg-primary"></div>
                                                    <div>
                                                        <h6 class="small fw-semibold mb-1">Submitted</h6>
                                                        <small class="text-muted">{{ $complaint->created_at->format('M d, Y') }}</small>
                                                    </div>
                                                </div>

                                                @if ($complaint->status !== 'pending')
                                                    <div class="timeline-item">
                                                        <div class="timeline-marker bg-info"></div>
                                                        <div>
                                                            <h6 class="small fw-semibold mb-1">{{ ucfirst($complaint->status) }}</h6>
                                                            <small class="text-muted">{{ $complaint->updated_at->format('M d, Y') }}</small>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
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
        @endforeach

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $complaints->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="card app-card">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox display-1 text-muted d-block mb-3"></i>
                <h5 class="text-muted">No complaints submitted yet</h5>
                <p class="text-muted small mb-3">Start by filing your first complaint</p>
                <a href="{{ route('resident.submit-complaint') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle-fill me-2"></i>File New Complaint
                </a>
            </div>
        </div>
    @endif
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline-item {
        display: flex;
        margin-bottom: 20px;
        position: relative;
    }

    .timeline-marker {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        position: absolute;
        left: -26px;
        top: 6px;
        border: 3px solid #2a2a2a;
    }
</style>

@endsection
