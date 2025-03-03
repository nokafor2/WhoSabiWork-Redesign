{{-- <div class="col-sm-8 col-4"> --}}
<div class="col-4">
    <div class="mb-3">
        <h3 class="display-7">
            @if ($businessCategory === 'artisan')
                <a href="{{ route('entrepreneur.show', ['entrepreneur' => $entrepreneur->id]) }}">Artisan Entrepreneur Details</a>
            @elseif ($businessCategory === 'technician')
                <a href="{{ route('entrepreneur.show', ['entrepreneur' => $entrepreneur->id]) }}">Technician Entrepreneur Details</a>   
            @elseif ($businessCategory === 'spare_part_seller')
                <a href="{{ route('entrepreneur.show', ['entrepreneur' => $entrepreneur->id]) }}">Spare Part Seller Entrepreneur Details</a>
            @elseif ($businessCategory === 'seller')
                <a href="{{ route('entrepreneur.show', ['entrepreneur' => $entrepreneur->id]) }}">Mobile Market Entrepreneur Details</a>
            @endif
        </h3>
        
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

        {{-- @tags(['tags' => $entrepreneur->tags])@endtags --}}

        {{-- <p>Viewed by {{ $counter }} person(s)</p> --}}
    </div>
</div>