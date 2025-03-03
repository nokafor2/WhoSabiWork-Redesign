@extends('layouts.app')

@section('title', 'Business Profile')

@section('content')
<div class="mx-auto my-3 text-center display-5">
    User profile
</div>
<div class="d-flex align-items-start">
    <div class="nav flex-column nav-pills me-3 col-md-2 col-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <button class="nav-link active text-start" id="businessDetails-tab" data-bs-toggle="pill" data-bs-target="#businessDetails" type="button" role="tab" aria-controls="businessDetails" aria-selected="true">User Details</button>
        <button class="nav-link text-start" id="comments-tab" data-bs-toggle="pill" data-bs-target="#comments" type="button" role="tab" aria-controls="comments" aria-selected="false">Comments</button>
        <button class="nav-link text-start" id="myAppointments-tab" data-bs-toggle="pill" data-bs-target="#myAppointments" type="button" role="tab" aria-controls="myAppointments" aria-selected="false">My Appointments</button>
    </div>
    <div class="tab-content col mb-3" id="v-pills-tabContent">
        <div class="tab-pane fade show active" id="businessDetails" role="tabpanel" aria-labelledby="businessDetails-tab" tabindex="0">
            <div class="accordion" id="accordionExample">
                @include('partials.userDetailsEditForm')
            </div>
        </div>

        <div class="tab-pane fade" id="comments" role="tabpanel" aria-labelledby="comments-tab" tabindex="0">
            Comments on entrepreneurs.
        </div>

        <div class="tab-pane fade" id="myAppointments" role="tabpanel" aria-labelledby="myAppointments-tab" tabindex="0">
            Appointments of user.
        </div>
    </div>
</div>
@endsection