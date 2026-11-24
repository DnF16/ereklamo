<div class="sidebar d-flex flex-column" id="appSidebar">

    <!-- Logo -->
    <div class="logo mb-4">
        <i class="bi bi-shield-check"></i>
        <span>e-REKLAMO</span>
    </div>

    <!-- User -->
    <div class="user-card mb-4">

        <div class="avatar">
            @if(auth()->user()->profile_picture)
                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}"
                    alt="Profile Picture"
                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            @else
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            @endif
        </div>

        <div class="user-info">
            <h6>{{ auth()->user()->name }}</h6>
            <small>{{ ucfirst(auth()->user()->role) }}</small>
        </div>

    </div>

    <!-- Menu -->
    <ul class="nav flex-column">

        <li class="nav-item">

            <a href="{{ route('resident.profile') }}" class="nav-link {{ request()->routeIs('resident.profile') ? 'active' : '' }}">

                <i class="bi bi-person-circle me-2"></i>

                Profile

            </a>

        </li>

        <li class="nav-item">

            <a href="{{ route('resident.dashboard') }}"
               class="nav-link">

                <i class="bi bi-grid-fill me-2"></i>

                Dashboard

            </a>

        </li>

        <li class="nav-item">

            <a href="{{ route('resident.submit-complaint') }}" class="nav-link {{ request()->routeIs('resident.submit-complaint') ? 'active' : '' }}">

                <i class="bi bi-pencil-square me-2"></i>

                Submit Complaint

            </a>

        </li>

        <!-- <li class="nav-item">

            <a href="{{ route('resident.complaints') }}"
               class="nav-link text-white">

                <i class="bi bi-card-list me-2"></i>

                My Complaints

            </a>

        </li> -->

        <!-- <li class="nav-item">

            <a href="{{ route('resident.notifications') }}"
               class="nav-link text-white">

                <i class="bi bi-bell-fill me-2"></i>

                Notifications

            </a>

        </li> -->

    </ul>

    <div class="mt-auto">

        <form action="{{ route('logout') }}" method="POST">

            @csrf

            <button class="btn btn-outline-danger w-100 rounded-3">

                <i class="bi bi-box-arrow-right me-2"></i>

                Logout

            </button>

        </form>

    </div>

</div>