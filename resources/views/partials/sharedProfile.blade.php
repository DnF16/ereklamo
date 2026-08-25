@php
    use Illuminate\Support\Str;
    $user = $user ?? auth()->user();
    $name = $user->name ?? 'Juan Dela Cruz';
    $email = $user->email ?? 'juan@email.com';
    $role = $user?->role ?? 'resident';
@endphp

<div class="py-4">

    @if(session('success'))
    <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3">
        <div id="successToast" class="toast show" role="alert">
            <div class="toast-header">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>

            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        let toastElement = document.getElementById('successToast');

        if (toastElement) {
            let toast = new bootstrap.Toast(toastElement, {
                delay: 3000 // 3 seconds
            });

            toast.show();
        }
    });
    </script>
    @endif

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h2 class="fw-bold  mb-1">My Profile</h2>
            <p class=" small mb-0">
                View and manage your personal information.
            </p>
        </div>

        @php
            $editUrl = ($user->role === 'staff' || $user->role === 'admin')
                ? route('admin.profile.edit')
                : route('resident.profile.edit');
            $updateRoute = ($user->role === 'staff' || $user->role === 'admin')
                ? route('admin.profile.update')
                : route('resident.profile.update');
            $passwordUpdateRoute = ($user->role === 'staff' || $user->role === 'admin')
                ? route('admin.profile.password.update')
                : route('resident.profile.password.update');
        @endphp

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#profileEditModal">
            <i class="bi bi-pencil-square me-2"></i>
            Edit Profile
        </button>
    </div>

    <!-- Profile Information -->
    <div class="row">

        <!-- Left Card -->
        <div class="col-lg-4 mb-4">

            <div class="card app-card border-0 shadow-sm">

                <div class="card-body text-center py-4">

                    <img src="https://via.placeholder.com/140"
                         class="rounded-circle border mb-3"
                         width="140"
                         height="140"
                         alt="Profile Picture">

                    <h4 class="fw-bold mb-1">
                        {{ $name }}
                    </h4>

                    <p class="text-muted mb-3">
                        {{ Str::title($role) }}
                    </p>

                    <hr>

                    <div class="text-start">

                        <p class="mb-2">
                            <i class="bi bi-envelope-fill text-primary me-2"></i>
                            {{ $email }}
                        </p>

                        <p class="mb-2">
                            <i class="bi bi-telephone-fill text-primary me-2"></i>
                            {{ $user->contact_number ?? '—' }}
                        </p>

                        <p class="mb-0">
                            <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                            {{ $user->address ?? '—' }}
                        </p>

                    </div>

                </div>

            </div>

        </div>

        <!-- Right Card -->
        <div class="col-lg-8">

            <!-- Personal Information -->
            <div class="card app-card mb-4 border-0 shadow-sm">

                <div class="card-header">
                    <h5 class="fw-bold mb-0">
                        Personal Information
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Full Name</label>
                            <div class="fw-semibold">
                                {{ $name }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Email</label>
                            <div class="fw-semibold">
                                {{ $email }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Contact Number</label>
                            <div class="fw-semibold">
                                {{$user->contact_number ?? '—'}}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Member Since</label>
                            <div class="fw-semibold">
                                {{ $user->created_at->format('F d, Y') }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="text-muted small">Address</label>
                            <div class="fw-semibold">
                                {{ $user->address ?? '—' }}
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <!-- Account Information -->
            <div class="card app-card mb-4 border-0 shadow-sm">

                <div class="card-header">
                    <h5 class="fw-bold mb-0">
                        Account Information
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">User Account</label>
                            <div class="fw-semibold">
                                {{ $email }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Password</label>
                            <div class="fw-semibold">
                                ************
                            </div>
                        </div>

                    </div>

                    <div class="mt-3">

                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#passwordChangeModal">
                            <i class="bi bi-key-fill me-2"></i>
                            Change Password
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- Profile Edit Modal -->
<div class="modal fade" id="profileEditModal" tabindex="-1" aria-labelledby="profileEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileEditModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form method="POST" action="{{ $updateRoute }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input name="name" class="form-control" value="{{ old('name', $user->name) }}">
                                @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input name="email" class="form-control" value="{{ old('email', $user->email) }}">
                                @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                                <label class="form-label">Contact Number</label>
                                <input name="contact_number" class="form-control" value="{{ old('contact_number', $user->contact_number) }}">
                                @error('contact_number')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control">{{ old('address', $user->address) }}</textarea>
                                @error('address')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button class="btn btn-primary">Save Changes</button>
                        </div>

                </form>

            </div>
        </div>
    </div>
</div>
<!-- Password Change Modal -->
<div class="modal fade" id="passwordChangeModal" tabindex="-1" aria-labelledby="passwordChangeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordChangeModalLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ $passwordUpdateRoute }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" autocomplete="current-password">
                        @error('current_password')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" autocomplete="new-password">
                        @error('password')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if ($errors->hasAny(['current_password', 'password']))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modalEl = document.getElementById('passwordChangeModal');
        if (modalEl) {
            var modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    });
</script>
@endif