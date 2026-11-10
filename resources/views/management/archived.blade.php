@extends('layouts.management')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold mb-1">Archived Complaints</h3>
        </div>

        <div>
            <button type="button"
                    class="btn btn-outline-danger me-2"
                    onclick="window.print()">
                <i class="bi bi-file-earmark-pdf-fill me-2"></i>
                Print / Save PDF
            </button>

            <button type="button"
                    class="btn btn-outline-success"
                    onclick="exportTableToCSV()">
                <i class="bi bi-file-earmark-excel-fill me-2"></i>
                Export CSV
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">

        <div class="col-md-3 mb-3">
            <div class="card app-card h-100">
                <div class="card-body">
                    <small class="text-muted">Total Archived</small>
                    <h2 class="fw-bold mb-0">
                        {{ $totalArchived ?? 0 }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card app-card h-100">
                <div class="card-body">
                    <small class="text-muted">Resolved</small>
                    <h2 class="fw-bold text-success mb-0">
                        {{ $resolvedCount ?? 0 }}
                    </h2>
                </div>
            </div>
        </div>

    </div>


    <!-- Archived Complaint Records -->
    <div class="card app-card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Archived Complaint Records</h5>

            <span class="badge bg-success">
                {{ $complaints->total() }} Records
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-dark align-middle mb-0"
                   id="archivedComplaintsTable">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Resident</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Description</th>
                        <th>Date Submitted</th>
                        <th>Last Updated</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($complaints as $complaint)
                        <tr>
                            <td>#{{ $complaint->id }}</td>

                            <td>
                                {{ $complaint->user->name ?? 'Unknown Resident' }}
                            </td>

                            <td>
                                {{ ucfirst(str_replace('_', ' ', $complaint->category)) }}
                            </td>

                            <td>
                                @php
                                    $priority = strtolower($complaint->priority ?? 'low');
                                    $priorityClass = match ($priority) {
                                        'high' => 'danger',
                                        'medium' => 'warning',
                                        default => 'success',
                                    };
                                @endphp

                                <span class="badge bg-{{ $priorityClass }}">
                                    {{ ucfirst($priority) }}
                                </span>
                            </td>

                            <td>
                                {{ \Illuminate\Support\Str::limit($complaint->description, 70) }}
                            </td>

                            <td>
                                {{ $complaint->created_at?->format('M d, Y') ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $complaint->updated_at?->format('M d, Y') ?? 'N/A' }}
                            </td>

                            <td>
                                <span class="badge bg-success">
                                    <i class="bi bi-archive me-1"></i>
                                    Archived / Resolved
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-archive fs-3 d-block mb-2"></i>
                                No archived complaints available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        @if($complaints->hasPages())
            {{ $complaints->links() }}
        @endif

    </div>

</div>

<script>
function exportTableToCSV() {
    const table = document.getElementById('archivedComplaintsTable');

    if (!table) return;

    const rows = Array.from(table.querySelectorAll('tr'));

    const csv = rows.map(row =>
        Array.from(row.querySelectorAll('th, td')).map(cell => {
            let value = cell.innerText.trim().replace(/\s+/g, ' ');
            return '"' + value.replace(/"/g, '""') + '"';
        }).join(',')
    ).join('\r\n');

    const blob = new Blob(['\uFEFF' + csv], {
        type: 'text/csv;charset=utf-8;'
    });

    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');

    link.href = url;
    link.download = 'archived-complaints-report.csv';
    link.click();

    URL.revokeObjectURL(url);
}
</script>



@endsection