@foreach ($sparePartColumns as $key => $sparePart)
    <div class="form-check form-check-inline">
        <input class="form-check-input" name="spare_parts[]" type="checkbox" id="{{ $key }}" value="{{ $key }}">
        <label class="form-check-label" for="{{ $key }}">{{ $sparePart }}</label>
    </div>    
@endforeach