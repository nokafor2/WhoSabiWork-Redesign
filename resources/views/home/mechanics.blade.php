@extends('layouts.app')

{{-- @section('title', 'Mechanics Page')

@section('content')
    <h1 class="display-5">
        Mechanics Page
    </h1>
@endsection --}}

@section('title', 'Mechanics Page')

@section('content')
    <h1 class="display-5">
        Mechanics Page
    </h1>

    @forelse ($addresses as $key => $address)
        {{-- Include will inherit all the variables from the loop --}}
        @include('partials.post')
    @empty
        <p>No address found!</p>    
    @endforelse
@endsection