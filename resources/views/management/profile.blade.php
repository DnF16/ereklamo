@extends('layouts.management')

@section('content')

    @include('partials.sharedProfile', ['user' => auth()->user()])

@endsection