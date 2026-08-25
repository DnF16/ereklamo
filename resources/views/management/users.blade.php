@extends('layouts.management')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">User Management</h3>

        <button
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#addUserModal">

            <i class="bi bi-person-plus-fill me-2"></i>
            Add User

        </button>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">

        <div class="col-md-3 mb-3">
            <div class="card app-card h-100">
                <div class="card-body">
                    <h6 class="text-muted">Total Users</h6>
                    <h2 class="fw-bold mb-0 text-primary">{{ $counts['total'] ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card app-card h-100">
                <div class="card-body">
                    <h6 class="text-muted">Residents</h6>
                    <h2 class="fw-bold mb-0 text-success">{{ $counts['residents'] ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card app-card h-100">
                <div class="card-body">
                    <small class="text-muted">Staff</small>
                    <h2 class="fw-bold mb-0 text-secondary">{{ $counts['staff'] ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card app-card h-100">
                <div class="card-body">
                    <small class="text-muted">Administrators</small>
                    <h2 class="fw-bold mb-0 text-warning">{{ $counts['admins'] ?? 0 }}</h2>
                </div>
            </div>
        </div>

    </div>

    <!-- Search & Filter -->
    <div class="card app-card mb-4">

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-5">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Search by name or email...">
                </div>

                <div class="col-md-3">
                    <select class="form-select">
                        <option>All Roles</option>
                        <option>Resident</option>
                        <option>Staff</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select class="form-select">
                        <option>All Status</option>
                        <option>Active</option>
                        <option>Inactive</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100">
                        Search
                    </button>
                </div>

            </div>

        </div>

    </div>

    <!-- Users Table -->
    <div class="card app-card ">

        <div class="card-header ">
            <h5 class="mb-0">Registered Users</h5>
        </div>

        <div class="table-responsive">

            <table class="table table-dark align-middle mb-0">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Date Registered</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>

                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>

                            <td>
                                <span class="badge bg-primary text-capitalize">
                                    {{ $user->role }}
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-{{ $user->deleted_at ? 'secondary' : 'success' }}">
                                    {{ $user->deleted_at ? 'Inactive' : 'Active' }}
                                </span>
                            </td>

                            <td>{{ $user->created_at?->format('F j, Y') }}</td>

                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No users found.
                            </td>
                        </tr>
                        @endforelse

                    </tbody>

            </table>

        </div>

    </div>

    <!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    <i class="bi bi-person-plus-fill me-2"></i>
                    Add New User
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input
                                type="text"
                                class="form-control"
                                name="name"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Address</label>
                            <input
                                type="email"
                                class="form-control"
                                name="email"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input
                                type="text"
                                class="form-control"
                                name="phone">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role</label>

                            <select
                                class="form-select"
                                name="role"
                                required>

                                <option value="">Select Role</option>
                                <option value="staff">Staff</option>
                            </select>

                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input
                                type="password"
                                class="form-control"
                                name="password"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Confirm Password
                            </label>

                            <input
                                type="password"
                                class="form-control"
                                name="password_confirmation"
                                required>

                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Address</label>

                            <textarea
                                class="form-control"
                                rows="3"
                                name="address">
                            </textarea>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="bi bi-check-circle me-2"></i>
                        Create User

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</div>

@endsection