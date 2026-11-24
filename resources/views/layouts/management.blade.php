<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Dashboard</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    @stack('styles')
</head>

<body>

<div>

    <button
        type="button"
        class="sidebar-toggle"
        data-sidebar-toggle
        aria-controls="appSidebar"
        aria-expanded="false"
        aria-label="Open navigation menu">
        <i class="bi bi-list" aria-hidden="true"></i>
    </button>
    <button
        type="button"
        class="sidebar-backdrop"
        data-sidebar-backdrop
        aria-label="Close navigation menu"
        hidden></button>

    {{-- Sidebar --}}
    @include('partials.sidebar.management')

    {{-- Main Content --}}
    <div class="dashboard-content">

        

        <div class="container-fluid p-4">
            @yield('content')
        </div>

    </div>

</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

@stack('scripts')

</body>
</html>