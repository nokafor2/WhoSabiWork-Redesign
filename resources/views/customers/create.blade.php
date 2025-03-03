@extends('layouts.app')

@section('title', 'Create the Address')

@section('content')
<form action="{{ route('artisans.store') }}" method="POST">
    @csrf
    @include('partials.form')
    <div class="mb-3">
        <input type="submit" value="Create" class="btn btn-success btn-block">
    </div>
</form>
@endsection