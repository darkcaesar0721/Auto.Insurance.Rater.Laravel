@extends('auth.master')

@section('content')

    <div class="card">
        <div class="card-body p-4">

            <div class="text-center w-75 m-auto">
                <a href="">
                    <span><img src="/images/insura-logo@2.png"></span>
                </a>
                <p class="text-muted mb-4 mt-3">Enter your email address and password to access admin panel.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger mb-4 mt-4 font-13">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="/admin/login" method="post">
                {{ csrf_field() }}
                <div class="form-group mb-3">
                    <label for="email">Email address</label>
                    <input class="form-control" type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="password">Password</label>
                    <input class="form-control" type="password" name="password" placeholder="Enter your password">
                </div>

                <div class="form-group mb-3">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="checkbox-signin" name="remember_me" checked>
                        <label class="custom-control-label" for="checkbox-signin">Remember me</label>
                    </div>
                </div>

                <div class="form-group mb-0 text-center">
                    <button class="btn btn-primary btn-block" type="submit"> Log In </button>
                </div>

            </form>
        </div>
    </div>

@endsection