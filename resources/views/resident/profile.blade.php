@extends('layouts.resident')

@section('content')

    @include('partials.sharedProfile', ['user' => auth()->user()])

@endsection
