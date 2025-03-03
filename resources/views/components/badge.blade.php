@if(!isset($show) || $show)
    <span class="badge badge-{{ $type ?? 'primary' }} text-{{ $type ?? 'primary' }}">
        {{ $slot }}
    </span>
@endif