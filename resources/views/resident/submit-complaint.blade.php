@extends('layouts.resident')

@section('content')
<div class="container-fluid py-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h2 class="fw-bold mb-1">Complaint Management</h2>
            <p class="text-muted small mb-0">Track, manage, and submit your community complaints easily.</p>
        </div>
        <button class="btn btn-primary shadow-sm px-4 py-2"
                data-bs-toggle="modal"
                data-bs-target="#complaintModal">
            <i class="bi bi-plus-circle-fill me-2"></i>File New Complaint
        </button>
    </div>

    <div class="card app-card mb-4">
        <div class="card-body row align-items-center">
            <div class="col-md-4 col-lg-3">
                <label for="statusFilter" class="form-label text-secondary small fw-semibold uppercase">
                    Filter by Status
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="bi bi-funnel"></i>
                    </span>
                    <select class="form-select border-start-0" id="statusFilter" name="status">
                        <option value="">All Complaints</option>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
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
                        @forelse ($complaints as $complaint)
                            <tr>
                                <td>{{ $complaint->id }}</td>
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
                                        <span class="badge bg-warning text-dark">{{ ucfirst($complaint->priority) }}</span>
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
                                <td colspan="7" class="text-center text-muted py-4">
                                    No complaints available.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>

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

<!-- Complaint Modal -->
<div class="modal fade" id="complaintModal" tabindex="-1" aria-labelledby="complaintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <form action="{{ route('resident.submit-complaint.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="modal-header py-3">
                    <h5 class="modal-title fw-bold" id="complaintModalLabel">
                        <i class="bi bi-pencil-square me-2 text-primary"></i>Submit New Complaint
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary text-white">Complaint Title</label>
                            <input type="text" class="form-control" name="title" placeholder="Brief summarizing headline" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary text-white">Category</label>
                            <select class="form-select" name="category" required>
                                <option selected disabled value="">Select Category...</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="noise">Noise</option>
                                <option value="safety">Safety</option>
                                <option value="cleanliness">Cleanliness</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="col-12">

                            <label class="form-label fw-semibold small text-secondary text-white">
                                Complaint Location
                            </label>

                            <div id="map" class="border rounded" style="height: 350px;"></div>

                            <small class="text-muted">
                                Click on the map to select the complaint location.
                            </small>

                            <input type="hidden" name="latitude" id="latitude" required>
                            <input type="hidden" name="longitude" id="longitude" required>

                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-secondary text-white">Detailed Description</label>
                            <textarea class="form-control" rows="4" name="description" placeholder="Provide complete details about the issue... " required></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-secondary text-white">Upload Evidence (Photos/Videos)</label>
                            <div class="card border-dashed p-3 text-center bg-light">
                                <input type="file" class="form-control d-none" id="evidenceFile" name="evidence">
                                <label for="evidenceFile" style="cursor: pointer;" class="mb-0 text-muted">
                                    <i class="bi bi-cloud-arrow-up display-6 text-primary mb-2 d-block"></i>
                                    <span class="fw-medium text-primary">Click to select files</span> or drag and drop here
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer  py-3 border-top-0">
                    <button type="button" class="btn btn-outline-secondary px-4 text-white" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Submit Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>



@push('scripts')
<script>

let map;
let marker;

const modal = document.getElementById('complaintModal');

modal.addEventListener('shown.bs.modal', function () {

    if (!map) {

        const engineersHill = [16.4074, 120.6021];

        map = L.map('map', {

            center: engineersHill,
            zoom: 17,
            minZoom: 16,
            maxZoom: 19,

            maxBounds: [

                [16.4054, 120.6005], // Southwest

                [16.4091, 120.6041]  // Northeast

            ],

            maxBoundsViscosity: 1.0

        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        map.on('click', function(e){

            if(marker){
                map.removeLayer(marker);
            }

            marker = L.marker(e.latlng).addTo(map);

            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;

        });

    }

    setTimeout(() => {
        map.invalidateSize();
    }, 100);

});

</script>
@endpush

@endsection