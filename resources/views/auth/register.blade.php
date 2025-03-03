@extends('layouts.app')

@section('title', 'Sign-Up')

@section('content')
<div class="mx-auto my-3 text-center display-5">
Create a profile
</div>
<ul class="nav nav-pills nav-fill mb-3 mx-2 fs-3 fw-bold" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-semibold" id="pills-user-tab" data-bs-toggle="pill" data-bs-target="#pills-user" type="button" role="tab" aria-controls="pills-user" aria-selected="true">User Profile</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pills-business-tab" data-bs-toggle="pill" data-bs-target="#pills-business" type="button" role="tab" aria-controls="pills-business" aria-selected="false">Business Profile</button>
    </li>
</ul>
<div class="tab-content" id="pills-tabContent">
    {{-- Form contents for the user profile --}}
    <div class="tab-pane fade show active" id="pills-user" role="tabpanel" aria-labelledby="pills-user-tab" tabindex="0">
        <form class="col"  action="{{ route('register') }}" method="POST">
            @csrf
            <div class="row g-2">
                {{-- First name input field --}}
                <div class="col-sm">
                    <div class="form-floating my-3">
                        <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="First name" required 
                        class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}">
                        <label for="first_name">First name</label>
            
                        @if ($errors->has('first_name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('first_name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                {{-- Last name input field --}}
                <div class="col-sm">
                    <div class="form-floating my-3">
                        <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Last name" required 
                        class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}">
                        <label for="last_name">Last name</label>
                        
                        @if ($errors->has('last_name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('last_name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="row g-2">
                {{-- Gender input field --}}
                <div class="col-sm">
                    <div class="form-floating my-3">
                        <select name="gender" id="gender" class="form-control" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        <label for="gender">Gender</label>
            
                        @if ($errors->has('gender'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('gender') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                {{-- Username input field --}}
                <div class="col-md">
                    <div class="form-floating my-3">
                        <input type="text" name="username" value="{{ old('username') }}" placeholder="Username" required 
                        class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}">
                        <label for="username">Username</label>
                        
                        @if ($errors->has('username'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
    
            <div class="row g-2">
                {{-- Email input field --}}
                <div class="col-sm">
                    <div class="form-floating my-3">
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required 
                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
                        <label for="email">Email</label>
                        
                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                {{-- Phone number input field --}}
                <div class="col-sm">
                    <div class="form-floating my-3">
                        <input type="tel" name="phone_number" value="{{ old('phone_number') }}" placeholder="Phone number" required 
                        class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}">
                        <label for="phone_number">Phone number</label>
                        
                        @if ($errors->has('phone_number'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('phone_number') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
    
            <div class="row g-2">
                {{-- Password input field --}}
                <div class="col-sm">
                    <div class="form-floating my-3">
                        <input type="password" name="password"  placeholder="Password" required 
                        class="form-control  {{ $errors->has('password') ? 'is-invalid' : '' }}">
                        <label for="password">Password</label>
                        <div class="form-text text-warning">
                            Password length is a minimum of 8 characters
                        </div>
            
                        @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                {{-- Confirm password input field --}}
                <div class="col-sm">
                    <div class="form-floating my-3">
                        <input type="password" name="password_confirmation" placeholder="Confirm password" required 
                        class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}">
                        <label for="password_comfirmation">Confirm Password</label>
                    </div>
                </div>
            </div>
    
            <div class="d-grid gap-2 col-sm-6 mx-auto">
                <button type="submit" name="user_register" class="btn btn-danger btn-block my-3 fs-4" value="true">Register</button>
            </div>
        </form>
    </div>

    {{-- Form contents for the business profile --}}
    <div class="tab-pane fade" id="pills-business" role="tabpanel" aria-labelledby="pills-business-tab" tabindex="0">
        {{-- was-validated --}}
        <form class="col needs-validation" novalidate  action="{{ route('register') }}" method="POST">
            @csrf
            <div class="row g-2">
                {{-- First name input field --}}
                <div class="col-sm">
                    <div class="form-floating my-3">
                        <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="First name" required 
                        class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}">
                        <label for="first_name">First name</label>
            
                        @if ($errors->has('first_name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('first_name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                {{-- Last name input field --}}
                <div class="col-sm">
                    <div class="form-floating my-3">
                        <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Last name" required 
                        class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}">
                        <label for="last_name">Last name</label>
                        
                        @if ($errors->has('last_name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('last_name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="row g-2">
                {{-- Gender input field --}}
                <div class="col-sm">
                    <div class="form-floating my-3">
                        <select name="gender" id="gender" class="form-control" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        <label for="gender">Gender</label>
            
                        {{-- @if ($errors->has('gender'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('gender') }}</strong>
                            </span>
                        @endif --}}
                        <div class="invalid-feedback">
                            Please select a gender.
                        </div>
                    </div>
                </div>
                {{-- Username input field --}}
                <div class="col-md">
                    <div class="form-floating my-3">
                        <input type="text" name="username" value="{{ old('username') }}" placeholder="Username" required 
                        class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}">
                        <label for="username">Username</label>
                        
                        @if ($errors->has('username'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
    
            <div class="row g-2">
                {{-- Email input field --}}
                <div class="col-sm">
                    <div class="form-floating my-3">
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required 
                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
                        <label for="email">Email</label>
                        
                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                {{-- Phone number input field --}}
                <div class="col-sm">
                    <div class="form-floating my-3">
                        <input type="tel" name="phone_number" value="{{ old('phone_number') }}" placeholder="Phone number" required 
                        class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}">
                        <label for="phone_number">Phone number</label>
                        
                        @if ($errors->has('phone_number'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('phone_number') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
    
            <div class="row g-2">
                {{-- Password input field --}}
                <div class="col-sm">
                    <div class="form-floating my-3">
                        <input type="password" name="password"  placeholder="Password" required 
                        class="form-control  {{ $errors->has('password') ? 'is-invalid' : '' }}">
                        <label for="password">Password</label>
                        <div class="form-text text-warning">
                            Password length is a minimum of 8 characters
                        </div>
            
                        @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                {{-- Confrim password input field --}}
                <div class="col-sm">
                    <div class="form-floating my-3">
                        <input type="password" name="password_confirmation" placeholder="Confirm password" required 
                        class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}">
                        <label for="password_comfirmation">Confirm Password</label>
                    </div>
                </div>
            </div>
    
            {{-- Business name input field --}}
            <div class="form-floating my-3">
                <input type="text" name="business_name" value="{{ old('business_name') }}" placeholder="Business name" required 
                class="form-control {{ $errors->has('business_name') ? 'is-invalid' : '' }}">
                <label for="business_name">Business name</label>
                
                @if ($errors->has('business_name'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('business_name') }}</strong>
                    </span>
                @endif
            </div>

            {{-- Address line 1 input field --}}
            <div class="form-floating my-3">
                <input type="text" name="address_line_1" value="{{ old('address_line_1') }}" placeholder="Address line 1" required 
                class="form-control {{ $errors->has('address_line_1') ? 'is-invalid' : '' }}">
                <label for="address_line_1">Address line 1</label>
                
                @if ($errors->has('address_line_1'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('address_line_1') }}</strong>
                    </span>
                @endif
            </div>
    
            <div class="row g-2">
                {{-- Address line 2 input field --}}
                <div class="col-sm">
                    <div class="form-floating my-3">
                        <input type="text" name="address_line_2" value="{{ old('address_line_2') }}" placeholder="Address line 2" 
                        class="form-control {{ $errors->has('address_line_2') ? 'is-invalid' : '' }}">
                        <label for="address_line_2">Address line 2 (optional)</label>
                        <div class="form-text text-warning">
                            This field is optional.
                        </div>
                        
                        @if ($errors->has('address_line_2'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('address_line_2') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                {{-- Address line 3 input field --}}
                <div class="col-sm">
                    <div class="form-floating my-3">
                        <input type="text" name="address_line_3" value="{{ old('address_line_3') }}" placeholder="Address line 3" 
                        class="form-control {{ $errors->has('address_line_3') ? 'is-invalid' : '' }}">
                        <label for="address_line_3">Address line 3</label>
                        <div class="form-text text-warning">
                            This field is optional.
                        </div>
                        
                        @if ($errors->has('address_line_3'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('address_line_3') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
    
            <div class="row g-2">
                {{-- Town input field --}}
                <div class="col-sm">
                    <div class="form-floating my-3">
                        <input type="text" name="town" value="{{ old('town') }}" placeholder="Town" required 
                        class="form-control {{ $errors->has('town') ? 'is-invalid' : '' }}">
                        <label for="town">Town</label>
                        
                        @if ($errors->has('town'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('town') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                {{-- State input field --}}
                <div class="col-sm">
                    <div class="form-floating my-3">
                        <input type="text" name="state" value="{{ old('state') }}" placeholder="State" required 
                        class="form-control {{ $errors->has('state') ? 'is-invalid' : '' }}">
                        <label for="state">State</label>
                        
                        @if ($errors->has('state'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('state') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
    
            {{-- Business category input field --}}
            {{-- <div class="form-floating my-3">
                <select name="business_category" id="business_category" class="form-control" required>
                    <option value="">Select Business Category</option>
                    <option value="artisan">Artisan</option>
                    <option value="seller">Seller</option>
                    <option value="mechanic">Mechanic</option>
                    <option value="spare_part_seller">Spare Part Seller</option>
                </select>
                <label for="state">Business Category</label>
    
                @if ($errors->has('business_category'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('business_category') }}</strong>
                    </span>
                @endif
            </div> --}}

            {{-- Business category input field --}}
            <div class="row">
                <label for="">Select Business Category:</label>
                <div class="col-sm-2 col">
                    <button class="btn " type="button" data-bs-toggle="collapse" data-bs-target="#artisansList" aria-expanded="false" aria-controls="artisansList">
                        <div class="form-check form-check-inline">
                            <input type="checkbox" name="business_categories[]" id="artisan" class="form-check-input" value="artisan" required>
                            <label for="artisan" class="form-check-label">Artisan</label>
                        </div>
                    </button>
                </div>
                <div class="col-sm-2 col">
                    <button class="btn " type="button" data-bs-toggle="collapse" data-bs-target="#sellersList" aria-expanded="false" aria-controls="sellersList">
                        <div class="form-check form-check-inline">
                            <input type="checkbox" name="business_categories[]" id="seller" class="form-check-input" value="seller" required>
                            <label for="seller" class="form-check-label">Mobile market</label>
                        </div>
                    </button>
                </div>
                <div class="col-sm-2 col">
                    <button class="btn " type="button" data-bs-toggle="collapse" data-bs-target="#mechanicsList" aria-expanded="false" aria-controls="mechanicsList">
                        <div class="form-check form-check-inline">
                            <input type="checkbox" name="business_categories[]" id="mechanic" class="form-check-input" value="mechanic" required>
                            <label for="mechanic" class="form-check-label">Auto Technician</label>
                        </div>                
                    </button>
                </div>
                <div class="col-sm-2 col">
                    <button class="btn " type="button" data-bs-toggle="collapse" data-bs-target="#sparePartSellersList" aria-expanded="false" aria-controls="sparePartSellersList">
                        <div class="form-check form-check-inline">
                            <input type="checkbox" name="business_categories[]" id="spare_part_seller" class="form-check-input" value="spare_part_seller">
                            <label for="spare_part_seller" class="form-check-label">Spare Part Seller</label>
                            <div class="invalid-feedback">Select at least one business category</div>
                        </div>                
                    </button>
                </div>
            </div>
    
            {{-- Artisans input field --}}
            <div class="collapse" id="artisansList">
                <p class="mt-3 mb-1">Artisans:</p>
                @include('partials.artisans')
            </div>
    
            {{-- Mobile market input field --}}
            <div class="collapse" id="sellersList">
                <p class="mt-3 mb-1">Mobile Markets:</p>
                @include('partials.products')
            </div>
    
            {{-- Technical service input field --}}
            <div class="collapse" id="mechanicsList">
                <p class="mt-3 mb-1">Technical Services:</p>
                @include('partials.technicalServices')
    
                <div>
                    <p class="mt-3 mb-1">Vehicle Categories</p>
                    <button class="btn " type="button" data-bs-toggle="collapse" data-bs-target="#carBrands" aria-expanded="false" aria-controls="carBrands">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="vehicle_category" id="car" value="car">
                            <label class="form-check-label" for="car">Cars</label>
                        </div>
                    </button>
                    <button class="btn " type="button" data-bs-toggle="collapse" data-bs-target="#busBrands" aria-expanded="false" aria-controls="busBrands">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="vehicle_category" id="bus" value="bus">
                            <label class="form-check-label" for="bus">Buses</label>
                        </div>
                    </button>
                    <button class="btn " type="button" data-bs-toggle="collapse" data-bs-target="#truckBrands" aria-expanded="false" aria-controls="truckBrands">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="vehicle_category" id="truck" value="truck">
                            <label class="form-check-label" for="truck">Trucks</label>
                        </div>
                    </button>
                </div>
    
                <div class="collapse" id="carBrands">
                    <p class="mt-3 mb-1">Car brands:</p>
                    @include('partials.carBrands')
                </div>
    
                <div class="collapse" id="busBrands">
                    <p class="mt-3 mb-1">Bus brands:</p>
                    @include('partials.busBrands')
                </div>
    
                <div class="collapse" id="truckBrands">
                    <p class="mt-3 mb-1">Truck brands:</p>
                    @include('partials.truckBrands')
                </div>
            </div>
    
            {{-- Spare part sellers input field --}}
            <div class="collapse" id="sparePartSellersList">
                <p class="mt-3 mb-1">Spare part sellers:</p>
                @include('partials.sparePartSellers')
    
                <div>
                    <p class="mt-3 mb-1">Vehicle Categories</p>
                    <button class="btn " type="button" data-bs-toggle="collapse" data-bs-target="#carBrands2" aria-expanded="false" aria-controls="carBrands2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="vehicle_category_spare" id="car" value="car">
                            <label class="form-check-label" for="car">Cars</label>
                        </div>
                    </button>
                    <button class="btn " type="button" data-bs-toggle="collapse" data-bs-target="#busBrands2" aria-expanded="false" aria-controls="busBrands2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="vehicle_category_spare" id="bus" value="bus">
                            <label class="form-check-label" for="bus">Buses</label>
                        </div>
                    </button>
                    <button class="btn " type="button" data-bs-toggle="collapse" data-bs-target="#truckBrands2" aria-expanded="false" aria-controls="truckBrands2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="vehicle_category_spare" id="truck" value="truck">
                            <label class="form-check-label" for="truck">Trucks</label>
                        </div>
                    </button>
                </div>
    
                <div class="collapse" id="carBrands2">
                    <p class="mt-3 mb-1">Car brands:</p>
                    @include('partials.carBrandsSpare')
                </div>
    
                <div class="collapse" id="busBrands2">
                    <p class="mt-3 mb-1">Bus brands:</p>
                    @include('partials.busBrandsSpare')
                </div>
    
                <div class="collapse" id="truckBrands2">
                    <p class="mt-3 mb-1">Truck brands:</p>
                    @include('partials.truckBrandsSpare')
                </div>
            </div>
    
            <div class="d-grid gap-2 col-sm-6 mx-auto">
                <button type="submit" name="business_register" class="btn btn-danger btn-block my-3 fs-4" value="true">Register</button>
            </div>
        </form>
    </div>
</div>    
@endsection