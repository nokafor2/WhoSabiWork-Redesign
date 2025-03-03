@extends('layouts.app')

@section('content')
    <form action="{{ route('login') }}" method="POST">
        @csrf
                
        <div class="form-group mt-3 mb-3">
            {{-- <label for="email">Email</label> --}}
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required 
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
            
            @if ($errors->has('password'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group mb-3">
            <div class="form-check">
                <input type="checkbox" name="remember" id="remember" class="form-check-input" value="{{ old('remember') ? 'checked' : '' }}">
                <label for="remember" class="form-check-lable">Remember Me</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block mb-3">Login</button>
    </form>
@endsection