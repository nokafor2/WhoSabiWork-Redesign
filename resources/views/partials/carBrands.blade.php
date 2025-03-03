@foreach ($carColumns as $key => $car)
    <div class="form-check form-check-inline">
        <input class="form-check-input" name="car_brands[]" type="checkbox" id="{{ $key }}" value="{{ $key }}">
        <label class="form-check-label" for="{{ $key }}">{{ $car }}</label>
    </div>    
@endforeach