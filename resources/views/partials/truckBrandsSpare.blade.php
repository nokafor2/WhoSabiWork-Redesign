@foreach ($truckColumns as $key => $truck)
    <div class="form-check form-check-inline">
        <input class="form-check-input" name="truck_brands_spare[]" type="checkbox" id="{{ $key }}" value="{{ $key }}">
        <label class="form-check-label" for="{{ $key }}">{{ $truck }}</label>
    </div>    
@endforeach