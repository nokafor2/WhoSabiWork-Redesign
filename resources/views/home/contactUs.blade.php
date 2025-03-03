@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
    <h1 class="display-5">
        Contact Page
    </h1>

    @can('home.secret')
        <p>
            <a href="{{ route('secret') }}">Go to special contact details.</a>
        </p>
    @endcan
@endsection