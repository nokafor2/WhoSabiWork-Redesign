@extends('layouts.app')

@section('title', 'Mobile Market Page')

@section('content')
    <h1 class="display-5">
        Mobile Market Page
    </h1>

    @forelse ($entrepreneurs as $key => $entrepreneur)
        {{-- Include will inherit all the variables from the loop --}}
        
        @include('partials.entrepreneurs')
    @empty
        <p>No Mobile Marketer found!</p>    
    @endforelse
@endsection