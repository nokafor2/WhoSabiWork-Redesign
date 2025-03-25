<template>
    <div class="mx-auto my-3 text-center display-5">
        {{ fullName }}
    </div>
    
    <div class="d-flex align-items-start justify-content-center">
        <div class="nav flex-column nav-pills me-3 col-md-2 col-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <button class="nav-link active text-start" id="businessDetails-tab" data-bs-toggle="pill" data-bs-target="#businessDetails" type="button" role="tab" aria-controls="businessDetails" aria-selected="true">Business Details</button>
            <button v-if="businessAccount" class="nav-link text-start" id="photoGallery-tab" data-bs-toggle="pill" data-bs-target="#photoGallery" type="button" role="tab" aria-controls="photoGallery" aria-selected="false">Photo Gallery</button>
            <button v-if="businessAccount" class="nav-link text-start" id="customersComments-tab" data-bs-toggle="pill" data-bs-target="#customersComments" type="button" role="tab" aria-controls="customersComments" aria-selected="false">Customers Comments</button>
            <button class="nav-link text-start" id="yourComments-tab" data-bs-toggle="pill" data-bs-target="#yourComments" type="button" role="tab" aria-controls="yourComments" aria-selected="false">My Comments</button>
            <button v-if="businessAccount" class="nav-link text-start" id="availability-tab" data-bs-toggle="pill" data-bs-target="#availability" type="button" role="tab" aria-controls="availability" aria-selected="false">Set Availability</button>
            <button v-if="businessAccount" class="nav-link text-start" id="customersAppointments-tab" data-bs-toggle="pill" data-bs-target="#customersAppointments" type="button" role="tab" aria-controls="customersAppointments" aria-selected="false">Customers Appointments</button>
            <button class="nav-link text-start" id="myAppointments-tab" data-bs-toggle="pill" data-bs-target="#myAppointments" type="button" role="tab" aria-controls="myAppointments" aria-selected="false">My Appointments</button>
        </div>
        <div class="tab-content col-9 col-md-8 mb-3" id="v-pills-tabContent">
            <!-- Business details pane -->
            <div class="tab-pane fade show active" id="businessDetails" role="tabpanel" aria-labelledby="businessDetails-tab" tabindex="0">
                <div class="accordion" id="accordionExample">
                    <!-- User Details section -->
                    <!-- Profile Photo Section -->
                    <ProfilePhotoSection2></ProfilePhotoSection2>
                    
                    <!-- {{-- First name accordion --}} -->
                    <FirstNameSection :firstName="user.first_name" :userId="user.id"></FirstNameSection>
                    <!-- <FieldAccordion :fieldName="'first_name'" :fieldVal="user.first_name" :userId="user.id"></FieldAccordion> -->
                    
                    <!-- {{-- Last name accordion --}} -->
                    <LastNameSection :lastName="user.last_name" :userId="user.id"></LastNameSection>
                    <!-- <FieldAccordion :fieldName="'last_name'" :fieldVal="user.last_name" :userId="user.id"></FieldAccordion> -->
                    
                    <!-- {{-- Gender accordion --}} -->
                    <GenderSection :gender="user.gender" :userId="user.id"></GenderSection>
                    
                    <!-- {{-- Username accordion --}} -->
                    <UsernameSection :username="user.username" :userId="user.id"></UsernameSection>                    
                    
                    <!-- {{-- Email accordion --}} -->
                    <EmailSection :email="user.email" :userId="user.id"></EmailSection>
                    
                    <!-- {{-- Phone number accordion --}} -->
                    <PhoneNumberSection :phoneNumber="user.phone_number" :userId="user.id"></PhoneNumberSection>
                    
                    <!-- {{-- Password accordion --}} -->
                    <PasswordSection :userId="user.id"></PasswordSection>

                    <!-- {{-- Business name accordion --}} -->
                    <BusinessNameSection v-if="businessAccount" :businessName="user.business_category.business_name" :userId="user.id"></BusinessNameSection>
                    <div v-if="businessAccount" class="accordion-item">
                        <h2 class="accordion-header" id="headingNine">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                                <dl class="row col-12 mb-0">
                                    <dt class="col-sm-3">Business name:</dt>
                                    <dd class="col-sm-9 mb-0">WhoSabiWork</dd>
                                </dl>
                            </button>
                        </h2>
                        <div id="collapseNine" class="accordion-collapse collapse" aria-labelledby="headingNine">
                            <div class="accordion-body">
                                <form class="" >
                                    <div class="form-group col-12 mb-3">
                                        <input type="text" name="businessName" value="" placeholder="Business name" required class="form-control">
                                    </div>
                                    
                                    <div class="row justify-content-between">
                                        <button type="submit" name="updateBusinessName" class="btn btn-danger col-auto">Update</button>
                                        <button type="submit" name="clearBusinessName" class="btn btn-danger col-auto">Clear</button>
                                    </div>
                                </form>   
                            </div>
                        </div>
                    </div>

                    <!-- {{-- Address Line 1 accordion --}} -->
                    <div v-if="businessAccount" class="accordion-item">
                        <h2 class="accordion-header" id="headingTen">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                                <dl class="row col-12 mb-0">
                                    <dt class="col-sm-3">Address Line 1:</dt>
                                    <dd class="col-sm-9 mb-0">33 Efab City Estate</dd>
                                </dl>
                            </button>
                        </h2>
                        <div id="collapseTen" class="accordion-collapse collapse" aria-labelledby="headingTen">
                            <div class="accordion-body">
                                <form class=""  action="{{ route('register') }}" method="POST">
                                    <!-- @csrf -->
                                    <div class="form-group col-12 mb-3">
                                        <input type="text" name="addressLine1" value="{{ old('addressLine1') }}" placeholder="Address Line 1" required 
                                        class="form-control {{ $errors->has('addressLine1') ? 'is-invalid' : '' }}">
                            
                                        <!-- @if ($errors->has('addressLine1'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('addressLine1') }}</strong>
                                            </span>
                                        @endif -->
                                    </div>
                                    
                                    <div class="row justify-content-between">
                                        <button type="submit" name="updateAddressLine1" class="btn btn-danger col-auto">Update</button>
                                        <button type="submit" name="clearAddressLine1" class="btn btn-danger col-auto">Clear</button>
                                    </div>
                                </form>   
                            </div>
                        </div>
                    </div>

                    <!-- {{-- Address Line 2 accordion --}} -->
                    <div v-if="businessAccount" class="accordion-item">
                        <h2 class="accordion-header" id="headingEleven">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
                                <dl class="row col-12 mb-0">
                                    <dt class="col-sm-3">Address Line 2:</dt>
                                    <dd class="col-sm-9 mb-0">A description list is perfect for defining terms.</dd>
                                </dl>
                            </button>
                        </h2>
                        <div id="collapseEleven" class="accordion-collapse collapse" aria-labelledby="headingEleven">
                            <div class="accordion-body">
                                <form class=""  action="{{ route('register') }}" method="POST">
                                    <!-- @csrf -->
                                    <div class="form-group col-12 mb-3">
                                        <input type="text" name="addressLine2" value="{{ old('addressLine2') }}" placeholder="Address Line 2" required 
                                        class="form-control {{ $errors->has('addressLine2') ? 'is-invalid' : '' }}">
                            
                                        <!-- @if ($errors->has('addressLine1'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('addressLine2') }}</strong>
                                            </span>
                                        @endif -->
                                    </div>
                                    
                                    <div class="row justify-content-between">
                                        <button type="submit" name="updateAddressLine2" class="btn btn-danger col-auto">Update</button>
                                        <button type="submit" name="clearAddressLine2" class="btn btn-danger col-auto">Clear</button>
                                    </div>
                                </form>   
                            </div>
                        </div>
                    </div>

                    <!-- {{-- Address Line 3 accordion --}} -->
                    <div v-if="businessAccount" class="accordion-item">
                        <h2 class="accordion-header" id="headingTwelve">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve">
                                <dl class="row col-12 mb-0">
                                    <dt class="col-sm-3">Address Line 3:</dt>
                                    <dd class="col-sm-9 mb-0">A description list is perfect for defining terms.</dd>
                                </dl>
                            </button>
                        </h2>
                        <div id="collapseTwelve" class="accordion-collapse collapse" aria-labelledby="headingTwelve">
                            <div class="accordion-body">
                                <form class=""  action="{{ route('register') }}" method="POST">
                                    <!-- @csrf -->
                                    <div class="form-group col-12 mb-3">
                                        <input type="text" name="addressLine3" value="{{ old('addressLine3') }}" placeholder="Address Line 3" required 
                                        class="form-control {{ $errors->has('addressLine3') ? 'is-invalid' : '' }}">
                            
                                        <!-- @if ($errors->has('addressLine3'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('addressLine3') }}</strong>
                                            </span>
                                        @endif -->
                                    </div>
                                    
                                    <div class="row justify-content-between">
                                        <button type="submit" name="updateAddressLine3" class="btn btn-danger col-auto">Update</button>
                                        <button type="submit" name="clearAddressLine3" class="btn btn-danger col-auto">Clear</button>
                                    </div>
                                </form>   
                            </div>
                        </div>
                    </div>

                    <!-- {{-- State accordion --}} -->
                    <div v-if="businessAccount" class="accordion-item">
                        <h2 class="accordion-header" id="headingThirteen">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThirteen" aria-expanded="false" aria-controls="collapseThirteen">
                                <dl class="row col-12 mb-0">
                                    <dt class="col-sm-3">State:</dt>
                                    <dd class="col-sm-9 mb-0">Abuja</dd>
                                </dl>
                            </button>
                        </h2>
                        <div id="collapseThirteen" class="accordion-collapse collapse" aria-labelledby="headingThirteen">
                            <div class="accordion-body">
                                <form class=""  action="{{ route('register') }}" method="POST">
                                    <!-- @csrf -->
                                    <div class="form-group col-12 mb-3">
                                        <input type="text" name="state" value="{{ old('state') }}" placeholder="State" required 
                                        class="form-control {{ $errors->has('state') ? 'is-invalid' : '' }}">
                            
                                        <!-- @if ($errors->has('state'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('state') }}</strong>
                                            </span>
                                        @endif -->
                                    </div>
                                    
                                    <div class="row justify-content-between">
                                        <button type="submit" name="updateState" class="btn btn-danger col-auto">Update</button>
                                        <button type="submit" name="clearState" class="btn btn-danger col-auto">Clear</button>
                                    </div>
                                </form>   
                            </div>
                        </div>
                    </div>

                    <!-- {{-- Town accordion --}} -->
                    <div v-if="businessAccount" class="accordion-item">
                        <h2 class="accordion-header" id="headingFourteen">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFourteen" aria-expanded="false" aria-controls="collapseFourteen">
                                <dl class="row col-12 mb-0">
                                    <dt class="col-sm-3">Town:</dt>
                                    <dd class="col-sm-9 mb-0">Mbora District</dd>
                                </dl>
                            </button>
                        </h2>
                        <div id="collapseFourteen" class="accordion-collapse collapse" aria-labelledby="headingFourteen">
                            <div class="accordion-body">
                                <form class=""  action="{{ route('register') }}" method="POST">
                                    <!-- @csrf -->
                                    <div class="form-group col-12 mb-3">
                                        <input type="text" name="town" value="{{ old('town') }}" placeholder="Town" required 
                                        class="form-control {{ $errors->has('town') ? 'is-invalid' : '' }}">
                            
                                        <!-- @if ($errors->has('town'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('town') }}</strong>
                                            </span>
                                        @endif -->
                                    </div>
                                    
                                    <div class="row justify-content-between">
                                        <button type="submit" name="updateTown" class="btn btn-danger col-auto">Update</button>
                                        <button type="submit" name="clearTown" class="btn btn-danger col-auto">Clear</button>
                                    </div>
                                </form>   
                            </div>
                        </div>
                    </div>
            
                    <!-- {{-- Business category accordion --}} -->
                    <div v-if="businessAccount" class="accordion-item">
                        <h2 class="accordion-header" id="headingFifteen">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFifteen" aria-expanded="false" aria-controls="collapseFifteen">
                                <dl class="row col-12 mb-0">
                                    <dt class="col-sm-3">Business Category:</dt>
                                    <dd class="col-sm-9 mb-0">Artisan</dd>
                                </dl>
                            </button>
                        </h2>
                        <div id="collapseFifteen" class="accordion-collapse collapse" aria-labelledby="headingFifteen">
                            <div class="accordion-body">
                                <form class=""  action="{{ route('register') }}" method="POST">
                                    <!-- @csrf -->
                                    <div class="form-group col-12 mb-3">
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" name="business_categories[]" id="artisan" class="form-check-input">
                                            <label for="artisan" class="form-check-label">Artisan</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" name="business_categories[]" id="seller" class="form-check-input">
                                            <label for="seller" class="form-check-label">Mobile market</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" name="business_categories[]" id="mechanic" class="form-check-input">
                                            <label for="mechanic" class="form-check-label">Mechanic</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" name="business_categories[]" id="spare_part_seller" class="form-check-input">
                                            <label for="spare_part_seller" class="form-check-label">Spare Part Seller</label>
                                        </div>
                                    </div>
                                    
                                    <div class="row justify-content-between">
                                        <button type="submit" name="updateBusinessCategory" class="btn btn-danger col-auto">Update</button>
                                        <button type="submit" name="clearBusinessCategory" class="btn btn-danger col-auto">Clear</button>
                                    </div>
                                </form>   
                            </div>
                        </div>
                    </div>
            
                    <!-- {{-- Artisans accordion --}} -->
                    <div v-if="businessAccount" class="accordion-item">
                        <h2 class="accordion-header" id="headingSixteen">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSixteen" aria-expanded="false" aria-controls="collapseSixteen">
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
                        <div id="collapseSixteen" class="accordion-collapse collapse" aria-labelledby="headingSixteen">
                            <div class="accordion-body">
                                <form class=""  action="{{ route('register') }}" method="POST">
                                    <!-- @csrf -->
                                    <div class="form-group col-12 mb-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="artisans[]" type="checkbox" id="{{ $key }}" value="{{ $key }}" required>
                                            <label class="form-check-label" for="{{ $key }}">Caterer</label>
                                            <!-- <div class="invalid-feedback">Select at least one business category</div> -->
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="artisans[]" type="checkbox" id="{{ $key }}" value="{{ $key }}" required>
                                            <label class="form-check-label" for="{{ $key }}">Tailor</label>
                                            <!-- <div class="invalid-feedback">Select at least one business category</div> -->
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="artisans[]" type="checkbox" id="{{ $key }}" value="{{ $key }}" required>
                                            <label class="form-check-label" for="{{ $key }}">Electrician</label>
                                            <!-- <div class="invalid-feedback">Select at least one business category</div> -->
                                        </div>
                                    </div>
                                    
                                    <div class="row justify-content-between">
                                        <button type="submit" name="updateArtisan" class="btn btn-danger col-auto">Update</button>
                                        <button type="submit" name="clearArtisan" class="btn btn-danger col-auto">Clear</button>
                                    </div>
                                </form>   
                            </div>
                        </div>
                    </div>
            
                    <!-- {{-- Mobile market accordion --}} -->
                    <div v-if="businessAccount" class="accordion-item">
                        <h2 class="accordion-header" id="headingSeventeen">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeventeen" aria-expanded="false" aria-controls="collapseSeventeen">
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
                        <div id="collapseSeventeen" class="accordion-collapse collapse" aria-labelledby="headingSeventeen">
                            <div class="accordion-body">
                                <form class=""  action="{{ route('register') }}" method="POST">
                                    <!-- @csrf -->
                                    <div class="form-group col-12 mb-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="products[]" type="checkbox" id="{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label" for="{{ $key }}">Shoes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="products[]" type="checkbox" id="{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label" for="{{ $key }}">Bags & Boxes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="products[]" type="checkbox" id="{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label" for="{{ $key }}">Electronics</label>
                                        </div>
                                    </div>
                                    
                                    <div class="row justify-content-between">
                                        <button type="submit" name="updateSeller" class="btn btn-danger col-auto">Update</button>
                                        <button type="submit" name="clearSeller" class="btn btn-danger col-auto">Clear</button>
                                    </div>
                                </form>   
                            </div>
                        </div>
                    </div>

                    <div v-if="businessAccount">For Mechanics and Spare Part Sellers</div>
    
                    <!-- {{-- Technicians accordion --}} -->
                    <div v-if="businessAccount" class="accordion-item">
                        <h2 class="accordion-header" id="headingEighteen">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEighteen" aria-expanded="false" aria-controls="collapseEighteen">
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
                        <div id="collapseEighteen" class="accordion-collapse collapse" aria-labelledby="headingEighteen">
                            <div class="accordion-body">
                                <form class=""  action="{{ route('register') }}" method="POST">
                                    <!-- @csrf -->
                                    <div class="form-group col-12 mb-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="technical_services[]" type="checkbox" id="{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label" for="{{ $key }}">Mechanic</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="technical_services[]" type="checkbox" id="{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label" for="{{ $key }}">Electrician</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="technical_services[]" type="checkbox" id="{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label" for="{{ $key }}">Computer diagnostic</label>
                                        </div>
                                    </div>
                                    
                                    <div class="row justify-content-between">
                                        <button type="submit" name="updateTechnicalServices" class="btn btn-danger col-auto">Update</button>
                                        <button type="submit" name="clearTechnicalServices" class="btn btn-danger col-auto">Clear</button>
                                    </div>
                                </form>   
                            </div>
                        </div>
                    </div>
            
                    <!-- {{-- Spare part sellers accordion --}} -->
                    <div v-if="businessAccount" class="accordion-item">
                        <h2 class="accordion-header" id="headingNineteen">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNineteen" aria-expanded="false" aria-controls="collapseNineteen">
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
                        <div id="collapseNineteen" class="accordion-collapse collapse" aria-labelledby="headingNineteen">
                            <div class="accordion-body">
                                <form class=""  action="{{ route('register') }}" method="POST">
                                    <!-- @csrf -->
                                    <div class="form-group col-12 mb-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="spare_parts[]" type="checkbox" id="{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label" for="{{ $key }}">Enginer part</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="spare_parts[]" type="checkbox" id="{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label" for="{{ $key }}">Tire</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="spare_parts[]" type="checkbox" id="{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label" for="{{ $key }}">Wheels</label>
                                        </div>
                                    </div>
                                    
                                    <div class="row justify-content-between">
                                        <button type="submit" name="updateSpareParts" class="btn btn-danger col-auto">Update</button>
                                        <button type="submit" name="clearSpareParts" class="btn btn-danger col-auto">Clear</button>
                                    </div>
                                </form>   
                            </div>
                        </div>
                    </div>

                    <!-- {{-- Car brands accordion --}} -->
                    <div v-if="businessAccount" class="accordion-item">
                        <h2 class="accordion-header" id="headingTwenty">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwenty" aria-expanded="false" aria-controls="collapseTwenty">
                                <dl class="row col-12 mb-0">
                                    <dt class="col-sm-3">Car brands:</dt>
                                    <dd class="col-sm-9 mb-0">
                                        <ul class="list-inline mb-0">
                                            <li class="list-inline-item">Toyota</li>
                                            <li class="list-inline-item">Nissan</li>
                                            <li class="list-inline-item">Honda</li>
                                        </ul>
                                    </dd>
                                </dl>
                            </button>
                        </h2>
                        <div id="collapseTwenty" class="accordion-collapse collapse" aria-labelledby="headingTwenty">
                            <div class="accordion-body">
                                <form class=""  action="{{ route('register') }}" method="POST">
                                    <!-- @csrf -->
                                    <div class="form-group col-12 mb-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="car_brands[]" type="checkbox" id="{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label" for="{{ $key }}">Toyota</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="car_brands[]" type="checkbox" id="{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label" for="{{ $key }}">Nissan</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="car_brands[]" type="checkbox" id="{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label" for="{{ $key }}">Honda</label>
                                        </div>
                                    </div>
                                    
                                    <div class="row justify-content-between">
                                        <button type="submit" name="updateCarBrands" class="btn btn-danger col-auto">Update</button>
                                        <button type="submit" name="clearCarBrands" class="btn btn-danger col-auto">Clear</button>
                                    </div>
                                </form>   
                            </div>
                        </div>
                    </div>

                    <!-- {{-- Bus brands accordion --}} -->
                    <div v-if="businessAccount" class="accordion-item">
                        <h2 class="accordion-header" id="headingTwentyOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwentyOne" aria-expanded="false" aria-controls="collapseTwentyOne">
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
                        <div id="collapseTwentyOne" class="accordion-collapse collapse" aria-labelledby="headingTwentyOne">
                            <div class="accordion-body">
                                <form class=""  action="{{ route('register') }}" method="POST">
                                    <!-- @csrf -->
                                    <div class="form-group col-12 mb-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="bus_Brands[]" type="checkbox" id="{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label" for="{{ $key }}">Toyota</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="bus_Brands[]" type="checkbox" id="{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label" for="{{ $key }}">Mercedes-Benz</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="bus_Brands[]" type="checkbox" id="{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label" for="{{ $key }}">Ford</label>
                                        </div>
                                    </div>
                                    
                                    <div class="row justify-content-between">
                                        <button type="submit" name="updateBusBrands" class="btn btn-danger col-auto">Update</button>
                                        <button type="submit" name="clearBusBrands" class="btn btn-danger col-auto">Clear</button>
                                    </div>
                                </form>   
                            </div>
                        </div>
                    </div>

                    <!-- {{-- Truck brnads accordion --}} -->
                    <div v-if="businessAccount" class="accordion-item">
                        <h2 class="accordion-header" id="headingTwentyTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwentyTwo" aria-expanded="false" aria-controls="collapseTwentyTwo">
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
                        <div id="collapseTwentyTwo" class="accordion-collapse collapse" aria-labelledby="headingTwentyTwo">
                            <div class="accordion-body">
                                <form class=""  action="{{ route('register') }}" method="POST">
                                    <!-- @csrf -->
                                    <div class="form-group col-12 mb-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="truck_brands[]" type="checkbox" id="{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label" for="{{ $key }}">Sinotruck</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="truck_brands[]" type="checkbox" id="{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label" for="{{ $key }}">HOWO</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="truck_brands[]" type="checkbox" id="{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label" for="{{ $key }}">MAN</label>
                                        </div>
                                    </div>
                                    
                                    <div class="row justify-content-between">
                                        <button type="submit" name="updateTruckBrands" class="btn btn-danger col-auto">Update</button>
                                        <button type="submit" name="clearTruckBrands" class="btn btn-danger col-auto">Clear</button>
                                    </div>
                                </form>   
                            </div>
                        </div>
                    </div>










                </div>
            </div>
            <!-- End of Business details pane -->
    
            <!-- Photo gallery -->
            <div v-if="businessAccount" class="tab-pane fade" id="photoGallery" role="tabpanel" aria-labelledby="photoGallery-tab" tabindex="0">
                <div class="gallery">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md 10">
                                <div class="row justify-content-evenly">
                                    
                                    <div class="card m-3 shadow" style="width: 200px;">
                                        <img :src=imagePath(1) class="card-img-top btn" height="174px" alt="" type="button"  data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        <p class="card-text mb-1 px-2">Image caption</p>
                                        
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1> -->
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img :src=imagePath(1) class="img-fluid" alt="">
                                                        <p class="card-text my-2 px-2 bg-dark text-light rounded">A beautiful cruise boat parked in the harbor waiting a beautiful environment.</p>
                                                        <a href="#" class="text-decoration-none me-3 text-body"><i class="fa-solid fa-thumbs-up pe-2"></i>20</a>
                                                        <div class="d-flex my-3">
                                                            <button class="btn btm-sm col-auto bg-danger text-light me-3">Edit Caption</button>
                                                            <button class="btn btm-sm col-auto bg-danger text-light me-3">Set Image as Cover Photo</button>
                                                            <button class="btn btm-sm col-auto bg-danger text-light">Delete Photo</button>
                                                        </div>

                                                        <div class="col-12 rounded bg-dark text-light p-2 mb-3">
                                                            <form action="">
                                                                <!-- <textarea class="rounded col-12 mt-3" name="" id="" placeholder="Enter your comment" rows="3"></textarea> -->
                                                                <textarea class="rounded form-control my-3" name="" id="" placeholder="Edit Caption" rows="3"></textarea>
                                                                <div class="d-flex justify-content-between">
                                                                    <button class="btn btn-sm btn-danger">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>

                                                        <div class="col-12 rounded bg-dark text-light p-2">
                                                            <p class="pt-2">5 comment(s)</p>
                                                            <div class="col-12 border-bottom pt-2">
                                                                <div class="row justify-content-between align-content-middle">
                                                                    <div class="col-auto">
                                                                        <div class="d-flex mt-2 mb-1 align-middle">
                                                                            <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(0)>
                                                                            <p class="col-auto mb-1">Nna-ayua Okafor</p>
                                                                        </div>
                                                                    </div>
                                                                    <p class="col-auto pt-2">December 18, 2024 at 5:18 PM</p>
                                                                </div>
                                                                <p class="col-12 bg-white text-body rounded">
                                                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo modi vero laboriosam suscipit! Enim reiciendis sunt debitis, ipsa sit ipsam amet obcaecati! Assumenda harum ipsam natus iste explicabo eos nemo?
                                                                </p>
                                                            </div>
                                                            <div class="col-12 border-bottom pt-2">
                                                                <div class="row justify-content-between align-content-middle">
                                                                    <div class="col-auto">
                                                                        <div class="d-flex mt-2 mb-1 align-middle">
                                                                            <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(0)>
                                                                            <p class="col-auto mb-1">Nna-ayua Okafor</p>
                                                                        </div>
                                                                    </div>
                                                                    <p class="col-auto pt-2">December 18, 2024 at 5:18 PM</p>
                                                                </div>
                                                                <p class="col-12 bg-white text-body rounded">
                                                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo modi vero laboriosam suscipit! Enim reiciendis sunt debitis, ipsa sit ipsam amet obcaecati! Assumenda harum ipsam natus iste explicabo eos nemo?
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card m-3 shadow" style="width: 200px;">
                                        <img :src=imagePath(2) class="card-img-top btn" height="174px" alt="" type="button"  data-bs-toggle="modal" data-bs-target="#exampleModal1">
                                        <p class="card-text mb-1 px-2">Image caption</p>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1> -->
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img :src=imagePath(2) class="img-fluid" alt="">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card m-3 shadow" style="width: 200px;">
                                        <img :src=imagePath(3) class="card-img-top btn" height="174px" alt="" type="button"  data-bs-toggle="modal" data-bs-target="#exampleModal2">
                                        <p class="card-text mb-1 px-2">Image caption</p>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1> -->
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img :src=imagePath(3) class="img-fluid" alt="">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card m-3 shadow" style="width: 200px;">
                                        <img :src=imagePath(4) class="card-img-top btn" height="174px" alt="" type="button"  data-bs-toggle="modal" data-bs-target="#exampleModal3">
                                        <p class="card-text mb-1 px-2">Image caption</p>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1> -->
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img :src=imagePath(4) class="img-fluid" alt="">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card m-3 shadow" style="width: 200px;">
                                        <img :src=imagePath(5) class="card-img-top btn" height="174px" alt="" type="button"  data-bs-toggle="modal" data-bs-target="#exampleModal4">
                                        <p class="card-text mb-1 px-2">Image caption</p>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal4" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1> -->
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img :src=imagePath(5) class="img-fluid" alt="">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card m-3 shadow" style="width: 200px;">
                                        <img :src=imagePath(6) class="card-img-top btn" height="174px" alt="" type="button"  data-bs-toggle="modal" data-bs-target="#exampleModal5">
                                        <p class="card-text mb-1 px-2">Image caption</p>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal5" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1> -->
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img :src=imagePath(6) class="img-fluid" alt="">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Photo gallery -->
    
            <!-- Contents for Customers Comments -->
            <div v-if="businessAccount" class="tab-pane fade" id="customersComments" role="tabpanel" aria-labelledby="customersComments-tab" tabindex="0">
                <div class="row justify-content-center">
                    <div class="col-md-10 col-xl-8 rounded text-light pb-3" style="background-color: #040D14">
                        <p class="pt-2">5 comment(s)</p>
                        <div class="col-12 border-bottom py-2">
                            <div class="row justify-content-between align-content-middle">
                                <div class="col-auto">
                                    <div class="d-flex mt-2 mb-1 align-middle">
                                        <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(0)>
                                        <p class="col-auto mb-1">Nna-ayua Okafor</p>
                                    </div>
                                </div>
                                <p class="col-auto pt-2">December 18, 2024 at 5:18 PM</p>
                            </div>
                            <p class="col-12 bg-white text-body rounded">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo modi vero laboriosam suscipit! Enim reiciendis sunt debitis, ipsa sit ipsam amet obcaecati! Assumenda harum ipsam natus iste explicabo eos nemo?
                            </p>
                            <div class="d-flex justify-content-end">
                                <button class="btn btm-sm border border-light text-light" style="background-color: #040D14">Reply</button>
                            </div>
                        </div>

                        <div class="col-12 border-bottom py-2">
                            <div class="row justify-content-between align-content-middle">
                                <div class="col-auto">
                                    <div class="d-flex mt-2 mb-1 align-middle">
                                        <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(0)>
                                        <p class="col-auto mb-1">Nna-ayua Okafor</p>
                                    </div>
                                </div>
                                <p class="col-auto pt-2">December 18, 2024 at 5:18 PM</p>
                            </div>
                            <p class="col-12 bg-white text-body rounded">
                                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ad cumque adipisci possimus voluptatum? Asperiores labore, adipisci id nostrum ab ut voluptatum accusantium? Nulla similique ut aspernatur laudantium. Debitis, vitae nostrum.
                            </p>
                            <div class="d-flex justify-content-end">
                                <button class="btn btm-sm border border-light text-light" style="background-color: #040D14">Reply</button>
                            </div>

                            <!-- reply -->
                            <div class="row justify-content-end">
                                <div class="col-10">
                                    <div class="row justify-content-between align-content-middle">
                                        <div class="col-auto">
                                            <div class="d-flex mt-2 mb-1 align-middle">
                                                <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(0)>
                                                <p class="col-auto mb-1">You replied</p>
                                            </div>
                                        </div>
                                        <p class="col-auto pt-2">December 18, 2024 at 5:18 PM</p>
                                    </div>
                                    <p class="col-12 bg-white text-body rounded">
                                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ea quia magni illo ipsam est ducimus accusantium quisquam libero ut soluta similique, natus molestiae voluptas architecto? Inventore excepturi aliquid soluta placeat!
                                    </p>
                                </div>
                            </div>

                            <div class="row justify-content-end">
                                <div class="col-10">
                                    <div class="row justify-content-between align-content-middle">
                                        <div class="col-auto">
                                            <div class="d-flex mt-2 mb-1 align-middle">
                                                <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(0)>
                                                <p class="col-auto mb-1">You replied</p>
                                            </div>
                                        </div>
                                        <p class="col-auto pt-2">December 18, 2024 at 5:18 PM</p>
                                    </div>
                                    <p class="col-12 bg-white text-body rounded">
                                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ea quia magni illo ipsam est ducimus accusantium quisquam libero ut soluta similique, natus molestiae voluptas architecto? Inventore excepturi aliquid soluta placeat!
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 border-bottom py-2">
                            <div class="row justify-content-between align-content-middle">
                                <div class="col-auto">
                                    <div class="d-flex mt-2 mb-1 align-middle">
                                        <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(0)>
                                        <p class="col-auto mb-1">Nna-ayua Okafor</p>
                                    </div>
                                </div>
                                <p class="col-auto pt-2">December 18, 2024 at 5:18 PM</p>
                            </div>
                            <p class="col-12 bg-white text-body rounded">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus iusto ut asperiores, aliquid repellat fugit nam omnis perspiciatis deserunt est, ea ex enim quae cum! Tempora veritatis similique error quos!
                            </p>
                            <textarea class="rounded form-control my-3" name="" id="" placeholder="Reply" rows="1"></textarea>
                            <div class="d-flex justify-content-end">
                                <button class="btn btm-sm border border-light text-light" style="background-color: #040D14">Reply</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Contents for Customers Comments -->
            
            <!-- Contents for My Comments -->
            <div class="tab-pane fade" id="yourComments" role="tabpanel" aria-labelledby="yourComments-tab" tabindex="0">
                <div class="row justify-content-center">
                    <div class="col-md-10 col-xl-8 rounded text-light pb-3" >  <!-- style="background-color: #040D14" -->
                        <p class="pt-2 text-body">5 comment(s)</p>
                        <div class="col-12 border-bottom pt-2">
                            <div class="row justify-content-between align-content-middle">
                                <div class="col-auto">
                                    <div class="d-flex mt-2 mb-1 align-middle">
                                        <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(0)>
                                        <p class="col-auto mb-1 text-body">Nna-ayua Okafor</p>
                                    </div>
                                </div>
                                <p class="col-auto pt-2 text-body">December 18, 2024 at 5:18 PM</p>
                            </div>
                            <p class="col-12 bg-white text-body rounded">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo modi vero laboriosam suscipit! Enim reiciendis sunt debitis, ipsa sit ipsam amet obcaecati! Assumenda harum ipsam natus iste explicabo eos nemo?
                            </p>
                        </div>

                        <div class="col-12 border-bottom pt-2">
                            <div class="row justify-content-between align-content-middle">
                                <div class="col-auto">
                                    <div class="d-flex mt-2 mb-1 align-middle">
                                        <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(0)>
                                        <p class="col-auto mb-1 text-body">Nna-ayua Okafor</p>
                                    </div>
                                </div>
                                <p class="col-auto pt-2 text-body">December 18, 2024 at 5:18 PM</p>
                            </div>
                            <p class="col-12 bg-white text-body rounded">
                                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ad cumque adipisci possimus voluptatum? Asperiores labore, adipisci id nostrum ab ut voluptatum accusantium? Nulla similique ut aspernatur laudantium. Debitis, vitae nostrum.
                            </p>
                        </div>

                        <div class="col-12 border-bottom pt-2">
                            <div class="row justify-content-between align-content-middle">
                                <div class="col-auto">
                                    <div class="d-flex mt-2 mb-1 align-middle">
                                        <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(0)>
                                        <p class="col-auto mb-1 text-body">Nna-ayua Okafor</p>
                                    </div>
                                </div>
                                <p class="col-auto pt-2 text-body">December 18, 2024 at 5:18 PM</p>
                            </div>
                            <p class="col-12 bg-white text-body rounded">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus iusto ut asperiores, aliquid repellat fugit nam omnis perspiciatis deserunt est, ea ex enim quae cum! Tempora veritatis similique error quos!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Contents for My Comments -->
    
            <!-- Contents for Set Availability -->
            <div v-if="businessAccount" class="tab-pane fade" id="availability" role="tabpanel" aria-labelledby="availability-tab" tabindex="0">
                Contents for Set Availability
            </div>
            <!-- End of Contents for Set Availability -->
    
            <!-- Contents for Customers Appointments -->
            <div v-if="businessAccount" class="tab-pane fade" id="customersAppointments" role="tabpanel" aria-labelledby="customersAppointments-tab" tabindex="0">                    
                <div class="row gap-3 gx-1">
                    <div class="col-md">
                        <p class="bg-danger text-light text-center">Appointments requested with technicians</p>
                        <div class="card col-12 mb-3">
                            <div class="card-body">
                                <h5 class="card-title text-center">Appointment Details</h5>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Technician Name:</p>
                                    <p class="card-text col-xs-8 ps-0 ps-sm-2 mb-3">Nna-ayua Okafor</p>
                                </div>                                    
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Phone Number:</p>
                                    <p class="card-text col-xs-8 ps-0 ps-sm-2 mb-3">09093336969</p>
                                </div>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Appointment Day:</p>
                                    <p class="card-text col-xs-8 ps-0 ps-sm-2 mb-3">Friday, December 20, 2024</p>
                                </div>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Appointment Time:</p>
                                    <p class="card-text col-xs-8 ps-0 ps-sm-2 mb-3">8:00 AM - 9:00 AM, 9:00 AM - 10:00 AM, 10:00 AM - 11:00 AM</p>
                                    <!-- <p class="card-text col-xs-8 ps-0 ps-sm-2 mb-3">
                                        <ul class="list-inline">
                                            <li class="list-inline-item">8:00 AM - 9:00 AM,</li>
                                            <li class="list-inline-item">9:00 AM - 10:00 AM,</li>
                                            <li class="list-inline-item">10:00 AM - 11:00 AM</li>
                                        </ul>
                                    </p> -->
                                </div>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Appointment Reason:</p>
                                    <p class="card-text col-xs-8 ps-0 ps-sm-2 mb-3">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam dolorum at sit numquam! Ut, ipsa! Ducimus animi sapiente iusto minima, repudiandae natus fugit eligendi sequi, beatae placeat nam modi optio?</p>
                                </div>
                            </div>
                        </div>

                        <div class="card col-12 mb-3">
                            <div class="card-body">
                                <h5 class="card-title text-center">Appointment Details</h5>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3"><span class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Technician Name:</span>
                                    Nna-ayua Okafor</p>
                                </div>                                    
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3"><span class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Phone Number:</span>
                                    09093336969</p>
                                </div>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3"><span class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Appointment Day:</span>
                                    Friday, December 20, 2024</p>
                                </div>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3"><span class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Appointment Time:</span>
                                    8:00 AM - 9:00 AM, 9:00 AM - 10:00 AM</p>
                                </div>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3"><span class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Appointment Reason:</span>
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam dolorum at sit numquam! Ut, ipsa! Ducimus animi sapiente iusto minima, repudiandae natus fugit eligendi sequi, beatae placeat nam modi optio?</p>
                                </div>
                            </div>
                        </div>

                        <div class="card col-12 mb-3">
                            <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-body-secondary">You have 1 appointment waiting confirmation</h6>
                            </div>
                        </div>

                        <div class="card col-12 mb-3">
                            <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-body-secondary">You have no appointment request declined.</h6>
                            </div>
                        </div>

                        <div class="card col-12 mb-3">
                            <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-body-secondary">You have no appointment request canceled.</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-md">
                        <p class="bg-danger text-light text-center">Appointments confirmed with technicians</p>
                        <div class="card col-12 mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-body-secondary">You have no appointment confirmed yet.</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Contents for Customers Appointments -->
    
            <!-- Contents for My Appointments -->
            <div class="tab-pane fade" id="myAppointments" role="tabpanel" aria-labelledby="myAppointments-tab" tabindex="0">                    
                <div class="row gap-3 gx-1">
                    <div class="col-md">
                        <p class="bg-danger text-light text-center">Appointments requested with technicians</p>
                        <div class="card col-12 mb-3">
                            <div class="card-body">
                                <h5 class="card-title text-center">Appointment Details</h5>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Technician Name:</p>
                                    <p class="card-text col-xs-8 ps-0 ps-sm-2 mb-3">Nna-ayua Okafor</p>
                                </div>                                    
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Phone Number:</p>
                                    <p class="card-text col-xs-8 ps-0 ps-sm-2 mb-3">09093336969</p>
                                </div>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Appointment Day:</p>
                                    <p class="card-text col-xs-8 ps-0 ps-sm-2 mb-3">Friday, December 20, 2024</p>
                                </div>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Appointment Time:</p>
                                    <p class="card-text col-xs-8 ps-0 ps-sm-2 mb-3">8:00 AM - 9:00 AM, 9:00 AM - 10:00 AM, 10:00 AM - 11:00 AM</p>
                                    <!-- <p class="card-text col-xs-8 ps-0 ps-sm-2 mb-3">
                                        <ul class="list-inline">
                                            <li class="list-inline-item">8:00 AM - 9:00 AM,</li>
                                            <li class="list-inline-item">9:00 AM - 10:00 AM,</li>
                                            <li class="list-inline-item">10:00 AM - 11:00 AM</li>
                                        </ul>
                                    </p> -->
                                </div>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Appointment Reason:</p>
                                    <p class="card-text col-xs-8 ps-0 ps-sm-2 mb-3">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam dolorum at sit numquam! Ut, ipsa! Ducimus animi sapiente iusto minima, repudiandae natus fugit eligendi sequi, beatae placeat nam modi optio?</p>
                                </div>
                            </div>
                        </div>

                        <div class="card col-12 mb-3">
                            <div class="card-body">
                                <h5 class="card-title text-center">Appointment Details</h5>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3"><span class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Technician Name:</span>
                                    Nna-ayua Okafor</p>
                                </div>                                    
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3"><span class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Phone Number:</span>
                                    09093336969</p>
                                </div>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3"><span class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Appointment Day:</span>
                                    Friday, December 20, 2024</p>
                                </div>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3"><span class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Appointment Time:</span>
                                    8:00 AM - 9:00 AM, 9:00 AM - 10:00 AM</p>
                                </div>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3"><span class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Appointment Reason:</span>
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam dolorum at sit numquam! Ut, ipsa! Ducimus animi sapiente iusto minima, repudiandae natus fugit eligendi sequi, beatae placeat nam modi optio?</p>
                                </div>
                            </div>
                        </div>

                        <div class="card col-12 mb-3">
                            <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-body-secondary">You have 1 appointment waiting confirmation</h6>
                            </div>
                        </div>

                        <div class="card col-12 mb-3">
                            <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-body-secondary">You have no appointment request declined.</h6>
                            </div>
                        </div>

                        <div class="card col-12 mb-3">
                            <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-body-secondary">You have no appointment request canceled.</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-md">
                        <p class="bg-danger text-light text-center">Appointments confirmed with technicians</p>
                        <div class="card col-12 mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-body-secondary">You have no appointment confirmed yet.</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Contents for My Appointments -->
        </div>
    </div>
</template>


<script>
    import FieldAccordion from './Components/FieldAccordion.vue';
    import ProfilePhotoSection from './Components/ProfilePhotoSection.vue';
    import ProfilePhotoSection2 from './Components/ProfilePhotoSection2.vue';
    import FirstNameSection from './Components/FirstNameSection.vue';
    import LastNameSection from './Components/LastNameSection.vue';
    import GenderSection from './Components/GenderSection.vue';
    import UsernameSection from './Components/UsernameSection.vue';
    import EmailSection from './Components/EmailSection.vue';
    import PhoneNumberSection from './Components/PhoneNumberSection.vue';
    import PasswordSection from './Components/PasswordSection.vue';
    import CommentCard from './Components/CommentCard.vue';
    import AppointmentDetails from './Components/AppointmentDetails.vue';
    import AppointmentDetails1 from './Components/AppointmentDetails1.vue';
    import BusinessNameSection from './Components/BusinessNameSection.vue';

    // import Mixins
    import MethodsMixin from './Mixins/MethodsMixin.js';

    export default {
        components: {FieldAccordion, ProfilePhotoSection, ProfilePhotoSection2, FirstNameSection, LastNameSection, GenderSection, UsernameSection, EmailSection, PhoneNumberSection, PasswordSection, CommentCard, AppointmentDetails, AppointmentDetails1, BusinessNameSection},
        mixins: [MethodsMixin],
        props: ['user', 'userCategories', 'vehicleBrands'],
        emits: [],
        data() {
            return {
                adImages: ['photoSample', 'photoSample1', 'photoSample2', 'photoSample3', 'photoSample4', 'photoSample5', 'photoSample6', 'photoSample7', 'photoSample8', 'photoSample9', 'photoSample10', 'photoSample11', 'photoSample12', 'photoSample13', 'photoSample14', 'photoSample15', 'photoSample16', 'photoSample17', 'photoSample18', 'photoSample19', 'photoSample20'],
            }
        },
        methods: {
            imagePath(index) {
                const imageName = this.adImages[index];
                const imageUrl = new URL(`../../../Images/${imageName}.jpg`, import.meta.url).href;

                return imageUrl;
            }
        },
        computed: {
            fullName() {
                return this.capitalizeFirstLetter(this.user.first_name) + " " + this.capitalizeFirstLetter(this.user.last_name);
            },
            businessAccount() {
                if (this.user.account_type === 'business') {
                    return true;
                } else {
                    return false;
                }
            },
            isArtisan() {
                return this.userCategories.artisan;
            },
            isMobileMarketer() {
                return this.userCategories.mobile_marketer;
            },
            isMechanic() {
                return this.userCategories.mechanic;
            },
            isSparePartSeller() {
                return this.userCategories.spare_part_seller;
            }
        }
    }
</script>