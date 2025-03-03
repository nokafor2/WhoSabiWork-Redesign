@extends('layouts.app')

@section('title', 'Spare Part Page')

@section('content')
    <h1 class="display-5">
        Spare Part Page
    </h1>

    @forelse ($entrepreneurs as $key => $entrepreneur)
        {{-- Include will inherit all the variables from the loop --}}
        @include('partials.entrepreneurs')
    @empty
        <p>No Spare Part Seller found!</p>    
    @endforelse
@endsection