<p class="text-muted">
    {{-- Added {{ $address->created_at->diffForHumans() }}
    by {{ $address->user->first_name }}, {{ $address->user->last_name }} --}}
    
    {{ empty(trim($slot)) ? 'Added' : $slot }} {{ $date->diffForHumans() }}
    @if (isset($first_name) && isset($last_name))
        by {{ $first_name }} {{ $last_name }}
    @endif
    
    @if (isset($name))
        by {{ $name }}
    @endif
</p>