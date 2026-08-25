@extends('layouts.management')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Reports</h3>

        <div>
            <button class="btn btn-outline-danger me-2">
                <i class="bi bi-file-earmark-pdf-fill me-2"></i>
                Export PDF
            </button>

            <button class="btn btn-outline-success">
                <i class="bi bi-file-earmark-excel-fill me-2"></i>
                Export Excel
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">

        <div class="col-md-3 mb-3">
            <div class="card app-card h-100">
                <div class="card-body">
                    <small class="text-muted">Total Complaints</small>
                    <h2 class="fw-bold mb-0">135</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card app-card h-100">
                <div class="card-body">
                    <small class="text-muted">Resolved</small>
                    <h2 class="fw-bold text-success mb-0">98</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card app-card h-100">
                <div class="card-body">
                    <small class="text-muted">Pending</small>
                    <h2 class="fw-bold text-warning mb-0">25</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card app-card h-100">
                <div class="card-body">
                    <small class="text-muted">High Priority</small>
                    <h2 class="fw-bold text-danger mb-0">12</h2>
                </div>
            </div>
        </div>

    </div>

    <!-- Filters -->
    <div class="card app-card mb-4">

        <!-- <div class="card-header bg-white">
            <h5 class="mb-0">Generate Report</h5>
        </div> -->

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-3">
                    <label class="form-label">From</label>
                    <input type="date" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">To</label>
                    <input type="date" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select class="form-select">
                        <option>All Categories</option>
                        <option>Garbage</option>
                        <option>Road</option>
                        <option>Water</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select">
                        <option>All Status</option>
                        <option>Pending</option>
                        <option>In Progress</option>
                        <option>Resolved</option>
                    </select>
                </div>

            </div>

        </div>

    </div>

    <div class="row">

        <!-- Complaint Summary -->
        <div class="col-lg-8 mb-4">

            <div class="card app-card h-100">

                <div class="card-header">
                    <h5 class="mb-0">Complaint Summary</h5>
                </div>

                <div class="table-responsive">

                    <table class="table table-dark mb-0">

                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Total</th>
                                <th>Resolved</th>
                                <th>Pending</th>
                            </tr>
                        </thead>

                        <tbody>

                            <tr>
                                <td>Garbage</td>
                                <td>35</td>
                                <td>30</td>
                                <td>5</td>
                            </tr>

                            <tr>
                                <td>Road</td>
                                <td>28</td>
                                <td>20</td>
                                <td>8</td>
                            </tr>

                            <tr>
                                <td>Water</td>
                                <td>20</td>
                                <td>18</td>
                                <td>2</td>
                            </tr>

                            <tr>
                                <td>Noise</td>
                                <td>15</td>
                                <td>10</td>
                                <td>5</td>
                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <!-- Report Summary -->
        <div class="col-lg-4 mb-4">

            <div class="card app-card h-100">

                <div class="card-header">
                    <h5 class="mb-0">AI Priority Summary</h5>
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">
                        <span>🔴 High</span>
                        <strong>12</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>🟡 Medium</span>
                        <strong>45</strong>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span>🟢 Low</span>
                        <strong>78</strong>
                    </div>

                    <hr>

                    <h6 class="fw-bold">Top Complaint Category</h6>

                    <p class="mb-0">
                        Garbage (35 complaints)
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection