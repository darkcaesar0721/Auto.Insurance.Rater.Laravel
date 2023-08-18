@extends('back-office.master')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Create User</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-sm-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-3 header-title">Create User Account</h4>

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4 mt-4 font-13">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    @if (session()->has('message'))
                        <div class="alert alert-success mb-4 mt-4 font-13">
                            {{ session()->get('message') }}
                        </div>
                    @endif

                    <form method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="nameInput">Name</label>
                            <input type="text" class="form-control" id="nameInput" name="name" placeholder="Enter name">
                        </div>
                        <div class="form-group">
                            <label for="emailInput">Email address</label>
                            <input type="email" class="form-control" id="emailInput" name="email" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="passwordInput">Password</label>
                            <input type="password" class="form-control" id="passwordInput" name="password" placeholder="********">
                        </div>
                        <div class="form-group">
                            <label for="passwordCInput">Password Confirmation</label>
                            <input type="password" class="form-control" id="passwordCInput" name="password_confirmation" placeholder="********">
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection