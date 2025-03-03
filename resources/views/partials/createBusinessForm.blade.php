<div class="form-group mt-3 mb-3">
    {{-- <label for="first_name">First name</label> --}}
    <input type="text" name="first_name" value="{{ old('first_name', optional($businessUser ?? null)->first_name) }}" placeholder="First name" required 
    class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}">

    @if ($errors->has('first_name'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('first_name') }}</strong>
        </span>
    @endif
</div>

<div class="form-group mb-3">
    {{-- <label for="last_name">Last name</label> --}}
    <input type="text" name="last_name" value="{{ old('last_name', optional($businessUser ?? null)->last_name) }}" placeholder="Last name" required 
    class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}">
    
    @if ($errors->has('last_name'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('last_name') }}</strong>
        </span>
    @endif
</div>

<div class="form-group mb-3">
    <select name="gender" id="gender" class="form-control" required>
        <option value="">Select Gender</option>
        <option value="male">Male</option>
        <option value="female">Female</option>
    </select>

    @if ($errors->has('gender'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('gender') }}</strong>
        </span>
    @endif
</div>

<div class="form-group mb-3">
    {{-- <label for="email">Email</label> --}}
    <input type="text" name="username" value="{{ old('username', optional($businessUser ?? null)->username) }}" placeholder="Username" required 
    class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}">
    
    @if ($errors->has('username'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('username') }}</strong>
        </span>
    @endif
</div>

<div class="form-group mb-3">
    {{-- <label for="email">Email</label> --}}
    <input type="email" name="email" value="{{ old('email', optional($businessUser ?? null)->email) }}" placeholder="Email" required 
    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
    
    @if ($errors->has('email'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
    @endif
</div>

<div class="form-group mb-3">
    {{-- <label for="password">Password</label> --}}
    <input type="password" name="password"  placeholder="Password" required 
    class="form-control  {{ $errors->has('password') ? 'is-invalid' : '' }}">
    <div class="form-text">
        Password lenght is a minimum of 8 characters
    </div>

    @if ($errors->has('password'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('password') }}</strong>
        </span>
    @endif
</div>

<div class="form-group mb-3">
    {{-- <label for="password_comfirmation">Confirm Password</label> --}}
    <input type="password" name="password_confirmation" placeholder="Confirm password" required 
    class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}">
</div>

<div class="form-group mb-3">
    {{-- <label for="email">Email</label> --}}
    <input type="tel" name="phone_number" value="{{ old('phone_number', optional($businessUser ?? null)->phone_number) }}" placeholder="Phone number" required 
    class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}">
    
    @if ($errors->has('phone_number'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('phone_number') }}</strong>
        </span>
    @endif
</div>

<div class="form-group mb-3">
    {{-- <label for="email">Email</label> --}}
    <input type="text" name="business_name" value="{{ old('business_name', optional($businessCategory ?? null)->business_name) }}" placeholder="Business name" required 
    class="form-control {{ $errors->has('business_name') ? 'is-invalid' : '' }}">
    
    @if ($errors->has('business_name'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('business_name') }}</strong>
        </span>
    @endif
</div>

{{-- <div class="form-group mb-3">
    State drop down option
</div>

<div class="form-group mb-3">
    Town drop down option
</div> --}}

<div class="form-group mb-3">
    <input type="text" name="address_line_1" value="{{ old('address_line_1', optional($address ?? null)->address_line_1) }}" placeholder="Address line 1" required 
    class="form-control {{ $errors->has('address_line_1') ? 'is-invalid' : '' }}">
    
    @if ($errors->has('address_line_1'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('address_line_1') }}</strong>
        </span>
    @endif
</div>

<div class="form-group mb-3">
    <input type="text" name="address_line_2" value="{{ old('address_line_2', optional($address ?? null)->address_line_2) }}" placeholder="Address line 2" 
    class="form-control {{ $errors->has('address_line_2') ? 'is-invalid' : '' }}">
    
    @if ($errors->has('address_line_2'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('address_line_2') }}</strong>
        </span>
    @endif
</div>

<div class="form-group mb-3">
    <input type="text" name="address_line_3" value="{{ old('address_line_3', optional($address ?? null)->address_line_3) }}" placeholder="Address line 3" 
    class="form-control {{ $errors->has('address_line_3') ? 'is-invalid' : '' }}">
    
    @if ($errors->has('address_line_3'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('address_line_3') }}</strong>
        </span>
    @endif
</div>

<div class="form-group mb-3">
    <input type="text" name="town" value="{{ old('town', optional($address ?? null)->town) }}" placeholder="Town" required 
    class="form-control {{ $errors->has('town') ? 'is-invalid' : '' }}">
    
    @if ($errors->has('town'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('town') }}</strong>
        </span>
    @endif
</div>

<div class="form-group mb-3">
    <input type="text" name="state" value="{{ old('state', optional($address ?? null)->state) }}" placeholder="State" required 
    class="form-control {{ $errors->has('state') ? 'is-invalid' : '' }}">
    
    @if ($errors->has('state'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('state') }}</strong>
        </span>
    @endif
</div>

{{-- <div class="form-group mb-3">
    Business category radio button
</div> --}}

<div class="form-group mb-3">
    <select name="business_category" id="business_category" class="form-control" required>
        <option value="">Select Business Category</option>
        <option value="artisan">Artisan</option>
        <option value="seller">Seller</option>
        <option value="mechanic">Mechanic</option>
        <option value="spare_part_seller">Spare Part Seller</option>
    </select>

    @if ($errors->has('business_category'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('business_category') }}</strong>
        </span>
    @endif
</div>