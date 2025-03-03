@extends('layouts.app')

@section('title', 'Update the Address')

@section('content')
<form action="{{ route('artisans.update', ['artisan' => $artisan->id]) }}" method="POST">
    @csrf
    @method('PUT')
    @include('partials.form')
    <div class="mb-3">
        <input type="submit" value="Update" class="btn btn-success btn-block">
    </div>
</form>
@endsection