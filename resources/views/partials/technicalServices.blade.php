@foreach ($technicalServiceColumns as $key => $service)
    <div class="form-check form-check-inline">
        <input class="form-check-input" name="technical_services[]" type="checkbox" id="{{ $key }}" value="{{ $key }}">
        <label class="form-check-label" for="{{ $key }}">{{ $service }}</label>
    </div>    
@endforeach