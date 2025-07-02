@extends('layouts/fullLayoutMaster')
@section('title', 'Login Page')

@section('content')
    <div class="auth-wrapper auth-cover">
        <div class="auth-inner row m-0">
            <!-- Brand logo-->
            <span class="brand-logo">
                <a href="" class="logo"> <img src="{{ asset('/assets/images/logo.png') }}" class="img-fluid"  style="height: 38px; padding-top: 5px;"/> </a>
              </span>
            <!-- /Brand logo-->
            <!-- Left Text-->
            <div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
                <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">

                        <img class="img-fluid" src="{{ asset('images/pages/login-v2.svg') }}" alt="Login V2" />

                </div>
            </div>
            <!-- /Left Text-->
            <!-- Login-->
            <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
                <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
                    <h2 class="card-title fw-bold mb-1">Hossain Litigation & Law Website Admin Panel</h2>
                    <p class="card-text mb-2">Please sign-in </p>

                    @if (session('success'))
                        <div class="alert alert-success p-2">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form class="auth-login-form mt-2" action="{{ route('loginConfirm') }}" method="POST">
                        @csrf
                        <div class="mb-1">
                            <label class="form-label" for="login-email">Email</label>
                            <input class="form-control" id="email" type="text" name="email"
                                placeholder="john@example.com" aria-describedby="login-email" autofocus="" tabindex="1"
                                value="{{ old('email') }}" />
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-1">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="login-password">Password</label>
                                <a href="{{ route('forgotPassword') }}">
                                    <small>Forgot Password?</small>
                                </a>
                            </div>
                            <div class="input-group input-group-merge form-password-toggle">
                                <input class="form-control form-control-merge" id="login-password" type="password"
                                    name="password" placeholder="············" aria-describedby="login-password"
                                    tabindex="2" />
                                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                            </div>
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-1">
                            {{-- <div class="form-check">
                                <input class="form-check-input" id="remember-me" type="checkbox" tabindex="3" />
                                <label class="form-check-label" for="remember-me"> Remember Me</label>
                            </div> --}}
                        </div>
                        @if (session('error'))
                            <div class="text-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <button class="btn btn-primary w-100" tabindex="4">Sign in</button>
                    </form>
                    {{-- <p class="text-center mt-2">
                        <span>New on our platform?</span>
                        <a href="{{ url('auth/register-cover') }}"><span>&nbsp;Create an account</span></a>
                    </p>
                    <div class="divider my-2">
                        <div class="divider-text">or</div>
                    </div>
                    <div class="auth-footer-btn d-flex justify-content-center">
                        <a class="btn btn-facebook" href="#"><i data-feather="facebook"></i></a>
                        <a class="btn btn-twitter white" href="#"><i data-feather="twitter"></i></a>
                        <a class="btn btn-google" href="#"><i data-feather="mail"></i></a>
                        <a class="btn btn-github" href="#"><i data-feather="github"></i></a>
                    </div> --}}
                </div>
            </div>
            <!-- /Login-->
        </div>
    </div>
@endsection
