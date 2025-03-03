@extends('layouts.app')

@section('title', 'Entrepreneur Page')

@section('content')
<div class="col-sm-8 col-4">
    <div class="mb-3">
        <h3 class="display-4">Entrepreneur Details</h3>
        
        <p>Name: {{ ucfirst($entrepreneur->first_name) }} {{ ucfirst($entrepreneur->last_name) }}</p>

        <p>Username: {{ $entrepreneur->username }}</p>

        @isset($entrepreneur->email)
        <p>Email: {{ $entrepreneur->email }}</p>
        @endisset

        @isset($entrepreneur->phone_number)
        <p>Phone number: {{ $entrepreneur->phone_number }}</p>
        @endisset

        @updated(['date' => $entrepreneur->created_at, 'name' => $entrepreneur->fullName($entrepreneur->id)])
        @endupdated

        @updated(['date' => $entrepreneur->created_at])
            Updated
        @endupdated

        @tags(['tags' => $entrepreneur->tags])@endtags

        {{-- <p>Viewed by {{ $counter }} person(s)</p> --}}
    </div>
    
    <div class="mb-3">
        <h4 class="display-7">Address</h4>
        @isset($address)
        <p>{{ $address }}</p>
        @endisset
        
        @isset($entrepreneur->address->created_at)
        <p>Added {{ $entrepreneur->address->created_at->diffForHumans() }}</p>
        
            @if (now()->diffInMinutes($entrepreneur->address->created_at) < 5)
                <div class="alert alert-info">New!</div>
            @endif
        @endisset
    </div>

    <div class="mb-3">
        <h3 class="display-7">Business Category</h3>
        
        <p>Business Name: {{ ucfirst($entrepreneur->businessCategory->business_name) }}</p>

        <p>Description : {{ $entrepreneur->businessCategory->business_description }}</p>

        <p>Webpage: {{ $entrepreneur->businessCategory->business_page }}</p>

        <p>Number of Views: {{ $entrepreneur->businessCategory->views }}</p>

        <p>Categories:
            @if($entrepreneur->businessCategory->artisan === 1)
                <span class="bg-danger badge badge-danger badge-lg">Artisan</span>
            @endif

            @if($entrepreneur->businessCategory->technician === 1)
                <span class="bg-danger badge badge-danger badge-lg">Technician</span>
            @endif

            @if($entrepreneur->businessCategory->seller === 1)
                <span class="bg-danger badge badge-danger badge-lg">Seller</span>
            @endif

            @if($entrepreneur->businessCategory->spare_part_seller === 1)
                <span class="bg-danger badge badge-danger badge-lg">Spare part</span>
            @endif
        </p>

        @isset($entrepreneur->email)
        <p>Email: {{ $entrepreneur->email }}</p>
        @endisset

        @isset($entrepreneur->phone_number)
        <p>Phone number: {{ $entrepreneur->phone_number }}</p>
        @endisset
    </div>
</div>
@endsection