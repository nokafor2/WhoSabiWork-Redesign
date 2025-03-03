@extends('layouts.app')

@section('title', 'Artisan Page')

@section('content')
<div class="col-sm-8 col-4">
    <div class="mb-3">
        <h3 class="display-4">User Details</h3>
        
        <p>Name: {{ ucfirst($user->first_name) }} {{ ucfirst($user->last_name) }}</p>

        <p>Username: {{ $user->username }}</p>

        @isset($user->email)
        <p>Email: {{ $user->email }}</p>
        @endisset

        @isset($user->phone_number)
        <p>Phone number: {{ $user->phone_number }}</p>
        @endisset

        @updated(['date' => $user->created_at, 'name' => $user->fullName($user->id)])
        @endupdated

        @updated(['date' => $user->created_at])
            Updated
        @endupdated

        @tags(['tags' => $user->tags])@endtags

        <p>Viewed by {{ $counter }} person(s)</p>
    </div>
    
    <div class="mb-3">
        <h4 class="display-5">Address</h4>
        @isset($user->address->address_line_1)
        <p>{{ $user->address->address_line_1 }}</p>
        @endisset
        
        @isset($user->address->address_line_2)
        <p>{{ $user->address->address_line_2 }}</p>
        @endisset
        
        @isset($user->address->address_line_3)
        <p>{{ $user->address->address_line_3 }}</p>
        @endisset
        
        @isset($user->address->town)
        <p>{{ $user->address->town }}, {{ $user->address->state }}</p>
        @endisset
        
        @isset($user->address->created_at)
        <p>Added {{ $user->address->created_at->diffForHumans() }}</p>
        
        @if (now()->diffInMinutes($user->address->created_at) < 5)
            <div class="alert alert-info">New!</div>
        @endif
        @endisset
    </div>

    <div class="mb-3">
        <h4 class="display-5">Upload Image</h4>
        @include('customers.imageUpload')
        {{-- Display images --}}
        {{-- <img src="{{ asset($user->photographs->first()->path) }}" /> --}}
        {{-- <img src="{{ Storage::url($user->photographs->first()->path) }}" />
        <img src="{{ $user->photographs->find(1)->url() }}" /> --}}
        
        {{-- check if image is set --}}
        @if ($user->photographs)                    
            {{-- <div style="background-image: url('{{ $user->photographs->first()->url() }}'); min-height: 500px; color: white; text-align: center; background-attachment: fixed;"></div> --}}
            <div style="background-image: url('{{ $user->photographs->url() }}'); min-height: 500px; color: black; text-align: center;">
                @if (isset($user->photographs->caption))
                    <h1>{{ $user->photographs->caption }}</h1>
                @endif
            </div>
        @endif
    </div>

    <div class="mb-3">
        <h4 class="display-5">Comments</h4>

        @include('comments._form')
        
        @forelse ($user->comments as $comment)
            <p>
                {{ $comment->body }}                
            </p>
            {{-- <p class="text-muted">
                added {{ $comment->created_at->diffForHumans() }}
            </p> --}}
            @updated(
                ['date' => $comment->created_at, 
                // 'name' => $comment->$user->fullName($user->id)
                'first_name' => $comment->user->first_name,
                'last_name' => $comment->user->last_name
                ])
            @endupdated
        @empty
            <p>No comments yet!</p>
        @endforelse
    </div>
</div>
<div class="col-4">
    @include('customers._activity')    
</div>
@endsection
