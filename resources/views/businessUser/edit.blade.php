@extends('layouts.app')

@section('title', 'Update the User')

@section('content')
<form action="{{ route('businessUser.update', ['businessUser' => $businessUser->id]) }}" method="POST">
    @csrf
    @method('PUT')
    @include('partials.createBusinessForm')
    <div class="mb-3">
        <input type="submit" value="Update" class="btn btn-success btn-block">
    </div>
</form>
@endsection