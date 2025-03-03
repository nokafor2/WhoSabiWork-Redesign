@extends('layouts.app')

@section('title', 'Customers Page')

@section('content')
    <h1 class="display-5">
        List of Users
    </h1>

    <div class="col-8">
        @forelse ($users as $key => $user)
            <div class="col-md-6">
                <h3 class="">
                    {{-- Use the softDelete trait method trashed() to check if model record was soft deleted. --}}
                    {{-- It retruns true if the model was soft deleted --}}
                    @if($user->trashed())
                        <del>
                    @endif
                    
                    <a class="{{ $user->trashed() ? 'text-muted' : '' }}" 
                        href="{{ route('artisans.show', ['artisan' => $user->id]) }}">User {{ $key }}</a>
                        
                    @if($user->trashed())
                        </del>
                    @endif

                    @badge(['type' => 'success', 'show' => now()->diffInMinutes($user->created_at) < 10])
                        Brand New User!
                    @endbadge
                </h3>

                <p>Name: {{ ucfirst($user->first_name) }} {{ ucfirst($user->last_name) }}</p>

                <p>Username: {{ $user->username }}</p>

                @isset($user->email)
                <p>Email: {{ $user->email }}</p>
                @endisset

                @isset($user->phone_number)
                <p>Phone number: {{ $user->phone_number }}</p>
                @endisset

                @if ($user->comments_count)
                    <p>{{ $user->comments_count }} comments</p>
                @else
                    <p>No comments yet!</p>
                @endif

                {{-- <p class="text-muted">
                    Added {{ $user->created_at->diffForHumans() }}
                </p> --}}

                @updated(['date' => $user->created_at])
                @endupdated

                @tags(['tags' => $user->tags])@endtags

                <div class="mb-3">
                    <a href="{{ route('businessUser.edit', ['businessUser' => $user->id]) }}" class="btn btn-primary btn-block">Edit</a>
                    @auth
                        @if(!$user->trashed())
                            <form class="d-inline" action="{{ route('businessUser.destroy', ['businessUser' => $user->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input class="btn btn-danger btn-block" type="submit" value="Delete!">
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        @empty
            <p>No user found!</p>    
        @endforelse
    </div>
    <div class="col-4">
        @include('customers._activity')    
    </div>
@endsection