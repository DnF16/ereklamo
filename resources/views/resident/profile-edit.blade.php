@extends('layouts.resident')

@section('content')

    @include('partials.profile', ['user' => $user])

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modalEl = document.getElementById('profileEditModal');
            if (modalEl) {
                var m = new bootstrap.Modal(modalEl);
                m.show();
            }
        });
    </script>

@endsection
