{{-- First name accordion --}}
<div class="accordion-item">
    <h2 class="accordion-header" id="headingOne">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
            <dl class="row col-12 mb-0">
                <dt class="col-sm-3">First Name:</dt>
                <dd class="col-sm-9 mb-0">A description list is perfect for defining terms.</dd>
            </dl>
        </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne">
        <div class="accordion-body">
            <form class=""  action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-group col-12 mb-3">
                    <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="First name" required 
                    class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}">
        
                    @if ($errors->has('first_name'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('first_name') }}</strong>
                        </span>
                    @endif
                </div>
                
                <div class="row justify-content-between">
                    <button type="submit" name="updateFirstName" class="btn btn-danger col-2">Update</button>
                    <button type="submit" name="clearFirstName" class="btn btn-danger col-2">Clear</button>
                </div>
            </form>   
        </div>
    </div>
</div>

{{-- Last name accordion --}}
<div class="accordion-item">
    <h2 class="accordion-header" id="headingTwo">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            <dl class="row col-12 mb-0">
                <dt class="col-sm-3">Last Name:</dt>
                <dd class="col-sm-9 mb-0">A description list is perfect for defining terms.</dd>
            </dl>
        </button>
    </h2>
    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo">
        <div class="accordion-body">
            <form class=""  action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-group col-12 mb-3">
                    <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Last name" required 
                    class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}">
        
                    @if ($errors->has('last_name'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('last_name') }}</strong>
                        </span>
                    @endif
                </div>
                
                <div class="row justify-content-between">
                    <button type="submit" name="updateLastName" class="btn btn-danger col-2">Update</button>
                    <button type="submit" name="clearLastName" class="btn btn-danger col-2">Clear</button>
                </div>
            </form>   
        </div>
    </div>
</div>

{{-- Gender accordion --}}
<div class="accordion-item">
    <h2 class="accordion-header" id="headingThree">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            <dl class="row col-12 mb-0">
                <dt class="col-sm-3">Gender:</dt>
                <dd class="col-sm-9 mb-0">A description list is perfect for defining terms.</dd>
            </dl>
        </button>
    </h2>
    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree">
        <div class="accordion-body">
            <form class=""  action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-group col-12 mb-3">
                    <select name="gender" id="gender" required
                    class="form-control {{ $errors->has('gender') ? 'is-invalid' : '' }}">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
        
                    @if ($errors->has('gender'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('gender') }}</strong>
                        </span>
                    @endif
                </div>
                
                <div class="row justify-content-between">
                    <button type="submit" name="updateGender" class="btn btn-danger col-2">Update</button>
                    <button type="submit" name="clearGender" class="btn btn-danger col-2">Clear</button>
                </div>
            </form>   
        </div>
    </div>
</div>

{{-- Username accordion --}}
<div class="accordion-item">
    <h2 class="accordion-header" id="headingFour">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
            <dl class="row col-12 mb-0">
                <dt class="col-sm-3">Username:</dt>
                <dd class="col-sm-9 mb-0">A description list is perfect for defining terms.</dd>
            </dl>
        </button>
    </h2>
    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour">
        <div class="accordion-body">
            <form class=""  action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-group col-12 mb-3">
                    <input type="text" name="username" value="{{ old('username') }}" placeholder="Username" required 
                    class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}">
        
                    @if ($errors->has('username'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                    @endif
                </div>
                
                <div class="row justify-content-between">
                    <button type="submit" name="updateUsername" class="btn btn-danger col-2">Update</button>
                    <button type="submit" name="clearUsername" class="btn btn-danger col-2">Clear</button>
                </div>
            </form>   
        </div>
    </div>
</div>

{{-- Email accordion --}}
<div class="accordion-item">
    <h2 class="accordion-header" id="headingFive">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
            <dl class="row col-12 mb-0">
                <dt class="col-sm-3">Email:</dt>
                <dd class="col-sm-9 mb-0">A description list is perfect for defining terms.</dd>
            </dl>
        </button>
    </h2>
    <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive">
        <div class="accordion-body">
            <form class=""  action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-group col-12 mb-3">
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required 
                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
        
                    @if ($errors->has('email'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                
                <div class="row justify-content-between">
                    <button type="submit" name="updateEmail" class="btn btn-danger col-2">Update</button>
                    <button type="submit" name="clearEmail" class="btn btn-danger col-2">Clear</button>
                </div>
            </form>   
        </div>
    </div>
</div>

{{-- Phone number accordion --}}
<div class="accordion-item">
    <h2 class="accordion-header" id="headingSix">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
            <dl class="row col-12 mb-0">
                <dt class="col-sm-3">Phone number:</dt>
                <dd class="col-sm-9 mb-0">A description list is perfect for defining terms.</dd>
            </dl>
        </button>
    </h2>
    <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix">
        <div class="accordion-body">
            <form class=""  action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-group col-12 mb-3">
                    <input type="tel" name="phoneNumber" value="{{ old('phoneNumber') }}" placeholder="Phone number" required 
                    class="form-control {{ $errors->has('phoneNumber') ? 'is-invalid' : '' }}">
        
                    @if ($errors->has('phoneNumber'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('phoneNumber') }}</strong>
                        </span>
                    @endif
                </div>
                
                <div class="row justify-content-between">
                    <button type="submit" name="updatePhoneNumber" class="btn btn-danger col-2">Update</button>
                    <button type="submit" name="clearPhoneNumber" class="btn btn-danger col-2">Clear</button>
                </div>
            </form>   
        </div>
    </div>
</div>

{{-- Password accordion --}}
<div class="accordion-item">
    <h2 class="accordion-header" id="headingSeven">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
            <dl class="row col-12 mb-0">
                <dt class="col-sm-3">Password:</dt>
                <dd class="col-sm-9 mb-0">A description list is perfect for defining terms.</dd>
            </dl>
        </button>
    </h2>
    <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven">
        <div class="accordion-body">
            <form class=""  action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-group col-12 mb-3">
                    <input type="password" name="password" value="{{ old('password') }}" placeholder="Password" required 
                    class="form-control {{ $errors->has('phoneNumber') ? 'is-invalid' : '' }}">
                    <input type="password" name="password_confirmation" placeholder="Confirm password" required 
                    class="mt-2 form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}">

                    @if ($errors->has('password'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                
                <div class="row justify-content-between">
                    <button type="submit" name="updatePassword" class="btn btn-danger col-2">Update</button>
                    <button type="submit" name="clearPassword" class="btn btn-danger col-2">Clear</button>
                </div>
            </form>   
        </div>
    </div>
</div>