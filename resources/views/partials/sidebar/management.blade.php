<aside class="sidebar d-flex flex-column">

    <!-- Logo -->
    <div class="logo mb-4">
        <!-- <i class="bi bi-shield-check"></i> -->
         <img src="{{ asset('images/logo') }}">
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
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i>
                Dashboard
            </a>
        </li>

        @if(auth()->user()?->role === 'admin')
        <li class="nav-item">
            <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                User Management
            </a>
        </li>
        @endif

        <li class="nav-item">
            <a href="{{ route('admin.complaints') }}" class="nav-link {{ request()->routeIs('admin.complaints') ? 'active' : '' }}">
                <i class="bi bi-chat-left-text-fill"></i>
                Complaints
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.archived') }}" class="nav-link {{ request()->routeIs('admin.archived') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-bar-graph-fill"></i>
                Archive Complaints
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.profile') }}" class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i>
                Profile
            </a>
        </li>

    </ul>

    <!-- Logout -->
    <div class="mt-auto">

        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button class="btn btn-outline-danger w-100 rounded-3">

                <i class="bi bi-box-arrow-right me-2"></i>

                Logout

            </button>

        </form>

    </div>

</aside>