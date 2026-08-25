<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-REKLAMO</title>

    <!-- Bootstrap (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Page CSS -->
    @stack('styles')
</head>

<body>

    <!-- ================= NAVBAR ================= -->

    <nav class="navbar navbar-dark border-bottom">
        <div class="container position-relative">

            <!-- Center Logo -->
            <div class="w-100 text-center">
                <a class="navbar-brand fw-bold fs-3 m-0" href="#">
                    <i class="bi bi-building"></i>
                    e-REKLAMO
                </a>
            </div>

            <!-- Right Buttons -->
            <div class="position-absolute top-50 end-0 translate-middle-y">
                <button class="btn btn-outline-primary me-2"
                        data-bs-toggle="modal"
                        data-bs-target="#loginModal">
                    Sign In
                </button>

                <button class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#registerModal">
                    Register
                </button>
            </div>

        </div>
    </nav>

    <!-- ================= HERO ================= -->

    <div class="container text-center py-5">
        <h2 class="fw-bold">
            Smart Barangay Complaint and Issue Tracking System
        </h2>

        <p >
            Official Online Complaint Platform of
            <strong>Barangay Engineer's Hill</strong>
        </p>
    </div>

    <!-- ================= LOGIN MODAL ================= -->

    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header position-relative">
                    <h5 class="modal-title w-100 text-center m-0">
                        Sign In
                    </h5>

                    <button type="button"
                            class="btn-close "
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <form action="{{ route('login.submit') }}" method="POST">

                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email</label>

                            <input type="email"
                                class="form-control"
                                name="email"
                                placeholder="Enter your email"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>

                            <input type="password"
                                class="form-control"
                                name="password"
                                placeholder="Enter your password"
                                required>
                        </div>

                        <button class="btn btn-primary w-100">
                            Sign In
                        </button>

                    </form>

                </div>

            </div>
        </div>
    </div>

    
    <!-- ================= REGISTER MODAL ================= -->

    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header position-relative">

                    <h5 class="modal-title w-100 text-center m-0">
                        Register
                    </h5>

                    <button type="button"
                            class="btn-close position-absolute end-0 me-3"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>

                </div>

                <div class="modal-body">

                    
                    <form action="{{ route('register.submit') }}" method="POST">

                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>

                            <input type="text"
                                class="form-control"
                                name="name"
                                placeholder="Enter your full name"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>

                            <input type="email"
                                class="form-control"
                                name="email"
                                placeholder="Enter your email"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>

                            <input type="password"
                                class="form-control"
                                name="password"
                                placeholder="Create a password"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>

                            <input type="password"
                                class="form-control"
                                name="password_confirmation"
                                placeholder="Confirm your password"
                                required>
                        </div>

                        <button class="btn btn-primary w-100">
                            Register
                        </button>

                    </form>

                </div>

            </div>
        </div>
    </div>

    @yield('content')

    <!-- Page JS -->
    @stack('scripts')

</body>
</html>