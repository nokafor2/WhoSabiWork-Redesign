<div class="col-4">
    <h3 class="">
        <a href="{{ route('artisans.show', ['artisan' => $address->id]) }}">Address {{ $key }}</a>
    </h3>

    <p>{{ $address->address_line_1 }}</p>

    @isset($address->address_line_2)
    <p>{{ $address->address_line_2 }}</p>
    @endisset

    @isset($address->address_line_3)
    <p>{{ $address->address_line_3 }}</p>
    @endisset

    <p>{{ $address->town }}, {{ $address->state }}</p>

    {{-- <p class="text-muted">
        Added {{ $address->created_at->diffForHumans() }}
        by {{ $address->user->first_name }}, {{ $address->user->last_name }}
    </p> --}}

    @updated(
        ['date' => $address->created_at, 
         'first_name' => $address->user->first_name,
         'last_name' => $address->user->last_name
        ])
    @endupdated

    <div class="mb-3">
        {{-- Use authorization with the can directive to determine if the button will be visible --}}
        @auth
            @can('update', $address)
                <a href="{{ route('artisans.edit', ['artisan' => $address->id]) }}" class="btn btn-primary btn-block">Edit</a>
            @endcan
        @endauth
        
        @auth
            @can('delete', $address)
                <form class="d-inline" action="{{ route('artisans.destroy', ['artisan' => $address->id]) }}" method="POST">
                    @csrf
                    {{-- Do a spoofing for deleteing with the POST method --}}
                    @method('DELETE')
                    <input class="btn btn-danger btn-block" type="submit" value="Delete!">
                </form>
            @endcan
        @endauth
    </div>
</div>