@extends('layouts.app')

@section('content')
    <h1>Login</h1>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        
        <div class="form-group">
            <label for="email">E-mail</label>
            <input name="email" value="{{ old('email')}}" required 
                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}">
            
            @if ($errors->has('email'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input name="password" required type="password"
                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}">

            @if ($errors->has('password'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
        
        <div class="form-group">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember"
                value="{{ old('remember') ? 'checked': '' }}">
    
                <label class="form-check-label" for="remember">
                    Remember Me
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>    
@endsection