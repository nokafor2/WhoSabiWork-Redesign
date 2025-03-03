@extends('layouts.app')

@section('title', 'Business Profile')

@section('content')
<div class="mx-auto my-3 text-center display-5">
Business profile
</div>
<div class="d-flex align-items-start">
    <div class="nav flex-column nav-pills me-3 col-md-2 col-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <button class="nav-link active text-start" id="businessDetails-tab" data-bs-toggle="pill" data-bs-target="#businessDetails" type="button" role="tab" aria-controls="businessDetails" aria-selected="true">Business Details</button>
        <button class="nav-link text-start" id="photoGallery-tab" data-bs-toggle="pill" data-bs-target="#photoGallery" type="button" role="tab" aria-controls="photoGallery" aria-selected="false">Photo Gallery</button>
        <button class="nav-link text-start" id="customersComments-tab" data-bs-toggle="pill" data-bs-target="#customersComments" type="button" role="tab" aria-controls="customersComments" aria-selected="false">Customers Comments</button>
        <button class="nav-link text-start" id="yourComments-tab" data-bs-toggle="pill" data-bs-target="#yourComments" type="button" role="tab" aria-controls="yourComments" aria-selected="false">Your Comments</button>
        <button class="nav-link text-start" id="availability-tab" data-bs-toggle="pill" data-bs-target="#availability" type="button" role="tab" aria-controls="availability" aria-selected="false">Set Availability</button>
        <button class="nav-link text-start" id="customersAppointments-tab" data-bs-toggle="pill" data-bs-target="#customersAppointments" type="button" role="tab" aria-controls="customersAppointments" aria-selected="false">Customers Appointments</button>
        <button class="nav-link text-start" id="myAppointments-tab" data-bs-toggle="pill" data-bs-target="#myAppointments" type="button" role="tab" aria-controls="myAppointments" aria-selected="false">My Appointments</button>
    </div>
    <div class="tab-content col mb-3" id="v-pills-tabContent">
        <div class="tab-pane fade show active" id="businessDetails" role="tabpanel" aria-labelledby="businessDetails-tab" tabindex="0">
            <div class="accordion" id="accordionExample">
                @include('partials.userDetailsEditForm')

                {{-- Business name accordion --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingEight">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            <dl class="row col-12 mb-0">
                                <dt class="col-sm-3">Business name:</dt>
                                <dd class="col-sm-9 mb-0">A description list is perfect for defining terms.</dd>
                            </dl>
                        </button>
                    </h2>
                    <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight">
                        <div class="accordion-body">
                            <form class=""  action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="form-group col-12 mb-3">
                                    <input type="text" name="businessName" value="{{ old('businessName') }}" placeholder="Business name" required 
                                    class="form-control {{ $errors->has('businessName') ? 'is-invalid' : '' }}">
                        
                                    @if ($errors->has('businessName'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('businessName') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="row justify-content-between">
                                    <button type="submit" name="updateBusinessName" class="btn btn-danger col-2">Update</button>
                                    <button type="submit" name="clearBusinessName" class="btn btn-danger col-2">Clear</button>
                                </div>
                            </form>   
                        </div>
                    </div>
                </div>

                {{-- Address Line 1 accordion --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingNine">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                            <dl class="row col-12 mb-0">
                                <dt class="col-sm-3">Address Line 1:</dt>
                                <dd class="col-sm-9 mb-0">A description list is perfect for defining terms.</dd>
                            </dl>
                        </button>
                    </h2>
                    <div id="collapseNine" class="accordion-collapse collapse" aria-labelledby="headingNine">
                        <div class="accordion-body">
                            <form class=""  action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="form-group col-12 mb-3">
                                    <input type="text" name="addressLine1" value="{{ old('addressLine1') }}" placeholder="Address Line 1" required 
                                    class="form-control {{ $errors->has('addressLine1') ? 'is-invalid' : '' }}">
                        
                                    @if ($errors->has('addressLine1'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('addressLine1') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="row justify-content-between">
                                    <button type="submit" name="updateAddressLine1" class="btn btn-danger col-2">Update</button>
                                    <button type="submit" name="clearAddressLine1" class="btn btn-danger col-2">Clear</button>
                                </div>
                            </form>   
                        </div>
                    </div>
                </div>

                {{-- Address Line 2 accordion --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTen">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                            <dl class="row col-12 mb-0">
                                <dt class="col-sm-3">Address Line 2:</dt>
                                <dd class="col-sm-9 mb-0">A description list is perfect for defining terms.</dd>
                            </dl>
                        </button>
                    </h2>
                    <div id="collapseTen" class="accordion-collapse collapse" aria-labelledby="headingTen">
                        <div class="accordion-body">
                            <form class=""  action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="form-group col-12 mb-3">
                                    <input type="text" name="addressLine2" value="{{ old('addressLine2') }}" placeholder="Address Line 2" required 
                                    class="form-control {{ $errors->has('addressLine2') ? 'is-invalid' : '' }}">
                        
                                    @if ($errors->has('addressLine1'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('addressLine2') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="row justify-content-between">
                                    <button type="submit" name="updateAddressLine2" class="btn btn-danger col-2">Update</button>
                                    <button type="submit" name="clearAddressLine2" class="btn btn-danger col-2">Clear</button>
                                </div>
                            </form>   
                        </div>
                    </div>
                </div>

                {{-- Address Line 3 accordion --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingEleven">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
                            <dl class="row col-12 mb-0">
                                <dt class="col-sm-3">Address Line 3:</dt>
                                <dd class="col-sm-9 mb-0">A description list is perfect for defining terms.</dd>
                            </dl>
                        </button>
                    </h2>
                    <div id="collapseEleven" class="accordion-collapse collapse" aria-labelledby="headingEleven">
                        <div class="accordion-body">
                            <form class=""  action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="form-group col-12 mb-3">
                                    <input type="text" name="addressLine3" value="{{ old('addressLine3') }}" placeholder="Address Line 3" required 
                                    class="form-control {{ $errors->has('addressLine3') ? 'is-invalid' : '' }}">
                        
                                    @if ($errors->has('addressLine3'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('addressLine3') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="row justify-content-between">
                                    <button type="submit" name="updateAddressLine3" class="btn btn-danger col-2">Update</button>
                                    <button type="submit" name="clearAddressLine3" class="btn btn-danger col-2">Clear</button>
                                </div>
                            </form>   
                        </div>
                    </div>
                </div>
                
                {{-- State accordion --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwelve">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve">
                            <dl class="row col-12 mb-0">
                                <dt class="col-sm-3">State:</dt>
                                <dd class="col-sm-9 mb-0">A description list is perfect for defining terms.</dd>
                            </dl>
                        </button>
                    </h2>
                    <div id="collapseTwelve" class="accordion-collapse collapse" aria-labelledby="headingTwelve">
                        <div class="accordion-body">
                            <form class=""  action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="form-group col-12 mb-3">
                                    <input type="text" name="state" value="{{ old('state') }}" placeholder="State" required 
                                    class="form-control {{ $errors->has('state') ? 'is-invalid' : '' }}">
                        
                                    @if ($errors->has('state'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('state') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="row justify-content-between">
                                    <button type="submit" name="updateState" class="btn btn-danger col-2">Update</button>
                                    <button type="submit" name="clearState" class="btn btn-danger col-2">Clear</button>
                                </div>
                            </form>   
                        </div>
                    </div>
                </div>
                
                {{-- Town accordion --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThirteen">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThirteen" aria-expanded="false" aria-controls="collapseThirteen">
                            <dl class="row col-12 mb-0">
                                <dt class="col-sm-3">Town:</dt>
                                <dd class="col-sm-9 mb-0">A description list is perfect for defining terms.</dd>
                            </dl>
                        </button>
                    </h2>
                    <div id="collapseThirteen" class="accordion-collapse collapse" aria-labelledby="headingThirteen">
                        <div class="accordion-body">
                            <form class=""  action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="form-group col-12 mb-3">
                                    <input type="text" name="town" value="{{ old('town') }}" placeholder="Town" required 
                                    class="form-control {{ $errors->has('town') ? 'is-invalid' : '' }}">
                        
                                    @if ($errors->has('town'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('town') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="row justify-content-between">
                                    <button type="submit" name="updateTown" class="btn btn-danger col-2">Update</button>
                                    <button type="submit" name="clearTown" class="btn btn-danger col-2">Clear</button>
                                </div>
                            </form>   
                        </div>
                    </div>
                </div>
                
                {{-- Business category accordion --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFourteen">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFourteen" aria-expanded="false" aria-controls="collapseFourteen">
                            <dl class="row col-12 mb-0">
                                <dt class="col-sm-3">Business Category:</dt>
                                <dd class="col-sm-9 mb-0">A description list is perfect for defining terms.</dd>
                            </dl>
                        </button>
                    </h2>
                    <div id="collapseFourteen" class="accordion-collapse collapse" aria-labelledby="headingFourteen">
                        <div class="accordion-body">
                            <form class=""  action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="form-group col-12 mb-3">
                                    @include('partials.businessCategories')
                                </div>
                                
                                <div class="row justify-content-between">
                                    <button type="submit" name="updateBusinessCategory" class="btn btn-danger col-2">Update</button>
                                    <button type="submit" name="clearBusinessCategory" class="btn btn-danger col-2">Clear</button>
                                </div>
                            </form>   
                        </div>
                    </div>
                </div>
                
                {{-- Artisans accordion --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFifteen">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFifteen" aria-expanded="false" aria-controls="collapseFifteen">
                            <dl class="row col-12 mb-0">
                                <dt class="col-sm-3">Artisans:</dt>
                                <dd class="col-sm-9 mb-0">
                                    <ul class="list-inline mb-0">
                                        <li class="list-inline-item">Caterer</li>
                                        <li class="list-inline-item">Tailor</li>
                                        <li class="list-inline-item">Electrician</li>
                                    </ul>
                                </dd>
                            </dl>
                        </button>
                    </h2>
                    <div id="collapseFifteen" class="accordion-collapse collapse" aria-labelledby="headingFifteen">
                        <div class="accordion-body">
                            <form class=""  action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="form-group col-12 mb-3">
                                    @include('partials.artisans')
                                </div>
                                
                                <div class="row justify-content-between">
                                    <button type="submit" name="updateArtisan" class="btn btn-danger col-2">Update</button>
                                    <button type="submit" name="clearArtisan" class="btn btn-danger col-2">Clear</button>
                                </div>
                            </form>   
                        </div>
                    </div>
                </div>
                
                {{-- Mobile market accordion --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingSixteen">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSixteen" aria-expanded="false" aria-controls="collapseSixteen">
                            <dl class="row col-12 mb-0">
                                <dt class="col-sm-3">Mobile market:</dt>
                                <dd class="col-sm-9 mb-0">
                                    <ul class="list-inline mb-0">
                                        <li class="list-inline-item">Shoes</li>
                                        <li class="list-inline-item">Bags & Boxes</li>
                                        <li class="list-inline-item">Electronics</li>
                                    </ul>
                                </dd>
                            </dl>
                        </button>
                    </h2>
                    <div id="collapseSixteen" class="accordion-collapse collapse" aria-labelledby="headingSixteen">
                        <div class="accordion-body">
                            <form class=""  action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="form-group col-12 mb-3">
                                    @include('partials.products')
                                </div>
                                
                                <div class="row justify-content-between">
                                    <button type="submit" name="updateSeller" class="btn btn-danger col-2">Update</button>
                                    <button type="submit" name="clearSeller" class="btn btn-danger col-2">Clear</button>
                                </div>
                            </form>   
                        </div>
                    </div>
                </div>

                <div>For Mechanics and Spare Part Sellers</div>

                {{-- Technicians accordion --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingSeventeen">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeventeen" aria-expanded="false" aria-controls="collapseSeventeen">
                            <dl class="row col-12 mb-0">
                                <dt class="col-sm-3">Technicians:</dt>
                                <dd class="col-sm-9 mb-0">
                                    <ul class="list-inline mb-0">
                                        <li class="list-inline-item">Mechanic</li>
                                        <li class="list-inline-item">Electrician</li>
                                        <li class="list-inline-item">Computer diagnostic</li>
                                    </ul>
                                </dd>
                            </dl>
                        </button>
                    </h2>
                    <div id="collapseSeventeen" class="accordion-collapse collapse" aria-labelledby="headingSeventeen">
                        <div class="accordion-body">
                            <form class=""  action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="form-group col-12 mb-3">
                                    @include('partials.technicalServices')
                                </div>
                                
                                <div class="row justify-content-between">
                                    <button type="submit" name="updateTechnicalServices" class="btn btn-danger col-2">Update</button>
                                    <button type="submit" name="clearTechnicalServices" class="btn btn-danger col-2">Clear</button>
                                </div>
                            </form>   
                        </div>
                    </div>
                </div>
                
                {{-- Spare part sellers accordion --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingEighteen">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEighteen" aria-expanded="false" aria-controls="collapseEighteen">
                            <dl class="row col-12 mb-0">
                                <dt class="col-sm-3">Spare part sellers:</dt>
                                <dd class="col-sm-9 mb-0">
                                    <ul class="list-inline mb-0">
                                        <li class="list-inline-item">Enginer part</li>
                                        <li class="list-inline-item">Tire</li>
                                        <li class="list-inline-item">Wheels</li>
                                    </ul>
                                </dd>
                            </dl>
                        </button>
                    </h2>
                    <div id="collapseEighteen" class="accordion-collapse collapse" aria-labelledby="headingEighteen">
                        <div class="accordion-body">
                            <form class=""  action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="form-group col-12 mb-3">
                                    @include('partials.sparePartSellers')
                                </div>
                                
                                <div class="row justify-content-between">
                                    <button type="submit" name="updateSpareParts" class="btn btn-danger col-2">Update</button>
                                    <button type="submit" name="clearSpareParts" class="btn btn-danger col-2">Clear</button>
                                </div>
                            </form>   
                        </div>
                    </div>
                </div>

                {{-- Car brands accordion --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingNineteen">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNineteen" aria-expanded="false" aria-controls="collapseNineteen">
                            <dl class="row col-12 mb-0">
                                <dt class="col-sm-3">Car brands:</dt>
                                <dd class="col-sm-9 mb-0">
                                    <ul class="list-inline mb-0">
                                        <li class="list-inline-item">Toyot</li>
                                        <li class="list-inline-item">Nissan</li>
                                        <li class="list-inline-item">Honda</li>
                                    </ul>
                                </dd>
                            </dl>
                        </button>
                    </h2>
                    <div id="collapseNineteen" class="accordion-collapse collapse" aria-labelledby="headingNineteen">
                        <div class="accordion-body">
                            <form class=""  action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="form-group col-12 mb-3">
                                    @include('partials.carBrands')
                                </div>
                                
                                <div class="row justify-content-between">
                                    <button type="submit" name="updateCarBrands" class="btn btn-danger col-2">Update</button>
                                    <button type="submit" name="clearCarBrands" class="btn btn-danger col-2">Clear</button>
                                </div>
                            </form>   
                        </div>
                    </div>
                </div>

                {{-- Bus brands accordion --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwenty">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwenty" aria-expanded="false" aria-controls="collapseTwenty">
                            <dl class="row col-12 mb-0">
                                <dt class="col-sm-3">Bus brands:</dt>
                                <dd class="col-sm-9 mb-0">
                                    <ul class="list-inline mb-0">
                                        <li class="list-inline-item">Toyota</li>
                                        <li class="list-inline-item">Mercedes-Benz</li>
                                        <li class="list-inline-item">Ford</li>
                                    </ul>
                                </dd>
                            </dl>
                        </button>
                    </h2>
                    <div id="collapseTwenty" class="accordion-collapse collapse" aria-labelledby="headingTwenty">
                        <div class="accordion-body">
                            <form class=""  action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="form-group col-12 mb-3">
                                    @include('partials.busBrands')
                                </div>
                                
                                <div class="row justify-content-between">
                                    <button type="submit" name="updateBusBrands" class="btn btn-danger col-2">Update</button>
                                    <button type="submit" name="clearBusBrands" class="btn btn-danger col-2">Clear</button>
                                </div>
                            </form>   
                        </div>
                    </div>
                </div>

                {{-- Truck brnads accordion --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwentyOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwentyOne" aria-expanded="false" aria-controls="collapseTwentyOne">
                            <dl class="row col-12 mb-0">
                                <dt class="col-sm-3">Truck brands:</dt>
                                <dd class="col-sm-9 mb-0">
                                    <ul class="list-inline mb-0">
                                        <li class="list-inline-item">Sinotruck</li>
                                        <li class="list-inline-item">HOWO</li>
                                        <li class="list-inline-item">MAN</li>
                                    </ul>
                                </dd>
                            </dl>
                        </button>
                    </h2>
                    <div id="collapseTwentyOne" class="accordion-collapse collapse" aria-labelledby="headingTwentyOne">
                        <div class="accordion-body">
                            <form class=""  action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="form-group col-12 mb-3">
                                    @include('partials.truckBrands')
                                </div>
                                
                                <div class="row justify-content-between">
                                    <button type="submit" name="updateTruckBrands" class="btn btn-danger col-2">Update</button>
                                    <button type="submit" name="clearTruckBrands" class="btn btn-danger col-2">Clear</button>
                                </div>
                            </form>   
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="photoGallery" role="tabpanel" aria-labelledby="photoGallery-tab" tabindex="0">
            Contents for Photo Gallery
        </div>

        <div class="tab-pane fade" id="customersComments" role="tabpanel" aria-labelledby="customersComments-tab" tabindex="0">
            Contents for Customers Comments
        </div>

        <div class="tab-pane fade" id="yourComments" role="tabpanel" aria-labelledby="yourComments-tab" tabindex="0">
            Contents for Your Comments
        </div>

        <div class="tab-pane fade" id="availability" role="tabpanel" aria-labelledby="availability-tab" tabindex="0">
            Contents for Set Availability
        </div>

        <div class="tab-pane fade" id="customersAppointments" role="tabpanel" aria-labelledby="customersAppointments-tab" tabindex="0">
            Contents for Customers Appointments
        </div>

        <div class="tab-pane fade" id="myAppointments" role="tabpanel" aria-labelledby="myAppointments-tab" tabindex="0">
            Contents for My Appointments
        </div>
    </div>
</div>
@endsection