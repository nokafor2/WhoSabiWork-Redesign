@foreach ($artisanColumns as $key => $artisan)
    <div class="form-check form-check-inline">
        <input class="form-check-input" name="artisans[]" type="checkbox" id="{{ $key }}" value="{{ $key }}" required>
        <label class="form-check-label" for="{{ $key }}">{{ $artisan }}</label>
        {{-- <div class="invalid-feedback">Select at least one business category</div> --}}
    </div>    
@endforeach