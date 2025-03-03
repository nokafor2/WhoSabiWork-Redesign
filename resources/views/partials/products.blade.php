@foreach ($productColumns as $key => $product)
    <div class="form-check form-check-inline">
        <input class="form-check-input" name="products[]" type="checkbox" id="{{ $key }}" value="{{ $key }}">
        <label class="form-check-label" for="{{ $key }}">{{ $product }}</label>
    </div>    
@endforeach