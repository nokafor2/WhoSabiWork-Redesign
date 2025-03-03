@foreach ($busColumns as $key => $bus)
    <div class="form-check form-check-inline">
        <input class="form-check-input" name="bus_Brands[]" type="checkbox" id="{{ $key }}" value="{{ $key }}">
        <label class="form-check-label" for="{{ $key }}">{{ $bus }}</label>
    </div>    
@endforeach