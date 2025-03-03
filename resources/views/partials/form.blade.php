
<div class="form-group mt-3 mb-3">
    {{-- <label for="address_line_1">Address Line 1</label>     --}}
    <input class="form-control {{ $errors->has('address_line_1') ? 'is-invalid' : '' }}" id="address_line_1" type="text" name="address_line_1" placeholder="Address Line 1" value="{{ old('addres_line_1', optional($artisan ?? null)->address_line_1) }}">

    @if ($errors->has('address_line_1'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('address_line_1') }}</strong>
        </span>
    @endif
</div>
{{-- Use @error directive when there is an error for a property --}}
@error('address_line_1')
    <div class="alert alert-danger mb-3">{{ $message }}</div>
@enderror

<div class="form-group mb-3">
    {{-- <label for="address_line_2">Address Line 2</label> --}}
    <input class="form-control" id="address_line_2" type="text" name="address_line_2" placeholder="Address Line 2" value="{{ old('addres_line_2', optional($artisan ?? null)->address_line_2) }}">
</div>
@error('address_line_2')
    <div class="alert alert-danger mb-3">{{ $message }}</div>
@enderror

<div class="form-group mb-3">
    {{-- <label for="address_line_3">Address Line 3</label> --}}
    <input class="form-control" id="address_line_3" type="text" name="address_line_3" placeholder="Address Line 3" value="{{ old('addres_line_3', optional($artisan ?? null)->address_line_3) }}">
</div>
@error('address_line_3')
    <div class="alert alert-danger mb-3">{{ $message }}</div>
@enderror

<div class="form-group mb-3">
    {{-- <label for="town">Town</label> --}}
    <input class="form-control" id="town" type="text" name="town" placeholder="Town" value="{{ old('town', optional($artisan ?? null)->town) }}">
</div>
@error('town')
    <div class="alert alert-danger mb-3">{{ $message }}</div>
@enderror

<div class="form-group mb-3">
    {{-- <label for="state">State</label> --}}
    <input class="form-control" id="state" type="text" name="state" placeholder="State" value="{{ old('state', optional($artisan ?? null)->state) }}">
</div>
@error('state')
    <div class="alert alert-danger mb-3">{{ $message }}</div>
@enderror

{{-- Check if any error exists --}}
@if ($errors->any())
    <div class="mb-3">
        <ul class="list-group">
            {{-- Loop through the errors --}}
            @foreach ($errors->all() as $error)
                <li class="list-group-item list-group-item-danger">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif