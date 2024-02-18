@extends('layouts.app')

@section('content')
{{-- Login form --}}
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header h3">Login</div>
                <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="inputEmail" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="inputEmail" aria-describedby="emailHelp" name="email" value="{{ old('email') }}" required autofocus>
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="inputPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="inputPassword" name="password" value="{{ old('password') }}" required>
                    @if ($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                    @endif
                </div>
                <div class="mb-3">
    <button type="submit" class="btn btn-primary w-100 mb-2">Login</button>
    <label class="form-label text-center d-block">Or</label>
    
    <a href="{{ route('register') }}" class="btn btn-primary w-100">Register</a>

</div>

               
            
            </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection