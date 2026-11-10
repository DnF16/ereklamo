<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>e-REKLAMO</title>

    <!-- Bootstrap via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

                <button
                    type="button"
                    class="btn btn-outline-primary me-2"
                    data-bs-toggle="modal"
                    data-bs-target="#loginModal">

                    Sign In

                </button>


                <button
                    type="button"
                    class="btn btn-primary"
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

        <p>
            Official Online Complaint Platform of
            <strong>Barangay Engineer's Hill</strong>
        </p>

    </div>



    <!-- ========================================================= -->
    <!-- LOGIN MODAL -->
    <!-- ========================================================= -->

    <div
        class="modal fade"
        id="loginModal"
        tabindex="-1"
        aria-labelledby="loginModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">


                <!-- HEADER -->
                <div class="modal-header position-relative">

                    <h5 class="modal-title w-100 text-center m-0">
                        Sign In
                    </h5>

                    <button
                        type="button"
                        class="btn-close position-absolute end-0 me-3"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>

                </div>


                <!-- BODY -->
                <div class="modal-body">

                    @php
                        $loginErrors = $errors->getBag('login');
                    @endphp


                    <form
                        action="{{ route('login.submit') }}"
                        method="POST">

                        @csrf


                        <!-- EMAIL -->
                        <div class="mb-3">

                            <label class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ session('open_modal') === 'login' ? old('email') : '' }}"
                                class="form-control {{ $loginErrors->has('email') ? 'is-invalid' : '' }}"
                                placeholder="Enter your email"
                                required>


                            @if ($loginErrors->has('email'))

                                <div class="invalid-feedback">
                                    {{ $loginErrors->first('email') }}
                                </div>

                            @endif

                        </div>



                        <!-- PASSWORD -->
                        <div class="mb-3">

                            <label class="form-label">
                                Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control {{ $loginErrors->has('password') ? 'is-invalid' : '' }}"
                                placeholder="Enter your password"
                                required>


                            @if ($loginErrors->has('password'))

                                <div class="invalid-feedback">
                                    {{ $loginErrors->first('password') }}
                                </div>

                            @endif

                        </div>



                        <!-- LOGIN BUTTON -->
                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            Sign In

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>



    <!-- ========================================================= -->
    <!-- REGISTER MODAL -->
    <!-- ========================================================= -->

    <div
        class="modal fade"
        id="registerModal"
        tabindex="-1"
        aria-labelledby="registerModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">


                <!-- HEADER -->
                <div class="modal-header position-relative">

                    <h5 class="modal-title w-100 text-center m-0">
                        Register
                    </h5>

                    <button
                        type="button"
                        class="btn-close position-absolute end-0 me-3"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>

                </div>



                <!-- BODY -->
                <div class="modal-body">

                    @php
                        $registerErrors = $errors->getBag('register');
                    @endphp


                    <form
                        action="{{ route('register.submit') }}"
                        method="POST">

                        @csrf


                        <!-- ================= FULL NAME ================= -->

                        <div class="mb-3">

                            <label class="form-label">
                                Full Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="form-control {{ $registerErrors->has('name') ? 'is-invalid' : '' }}"
                                placeholder="Enter your full name"
                                required>


                            @if ($registerErrors->has('name'))

                                <div class="invalid-feedback">
                                    {{ $registerErrors->first('name') }}
                                </div>

                            @endif

                        </div>



                        <!-- ================= EMAIL ================= -->

                        <div class="mb-3">

                            <label class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ session('open_modal') === 'register' ? old('email') : '' }}"
                                class="form-control {{ $registerErrors->has('email') ? 'is-invalid' : '' }}"
                                placeholder="Enter your email"
                                required>


                            @if ($registerErrors->has('email'))

                                <div class="invalid-feedback">
                                    {{ $registerErrors->first('email') }}
                                </div>

                            @endif

                        </div>



                        <!-- ================= PASSWORD ================= -->

                        <div class="mb-3">

                            <label class="form-label">
                                Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control {{ $registerErrors->has('password') ? 'is-invalid' : '' }}"
                                placeholder="Create a password"
                                required>


                            @if ($registerErrors->has('password'))

                                <div class="invalid-feedback">
                                    {{ $registerErrors->first('password') }}
                                </div>

                            @endif

                        </div>



                        <!-- ================= CONFIRM PASSWORD ================= -->

                        <div class="mb-3">

                            <label class="form-label">
                                Confirm Password
                            </label>

                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control {{ $registerErrors->has('password_confirmation') ? 'is-invalid' : '' }}"
                                placeholder="Confirm your password"
                                required>


                            @if ($registerErrors->has('password_confirmation'))

                                <div class="invalid-feedback">
                                    {{ $registerErrors->first('password_confirmation') }}
                                </div>

                            @endif

                        </div>



                        <!-- ================= REGISTER BUTTON ================= -->

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            Register

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>



    <!-- ========================================================= -->
    <!-- AUTOMATICALLY OPEN THE CORRECT MODAL AFTER VALIDATION -->
    <!-- ========================================================= -->

    @if (session('open_modal') === 'register')

        <script>
            document.addEventListener('DOMContentLoaded', function () {

                const registerModalElement =
                    document.getElementById('registerModal');

                if (registerModalElement) {

                    const registerModal =
                        bootstrap.Modal.getOrCreateInstance(
                            registerModalElement
                        );

                    registerModal.show();
                }

            });
        </script>

    @elseif (session('open_modal') === 'login')

        <script>
            document.addEventListener('DOMContentLoaded', function () {

                const loginModalElement =
                    document.getElementById('loginModal');

                if (loginModalElement) {

                    const loginModal =
                        bootstrap.Modal.getOrCreateInstance(
                            loginModalElement
                        );

                    loginModal.show();
                }

            });
        </script>

    @endif


    @yield('content')

    @stack('scripts')

</body>

</html>