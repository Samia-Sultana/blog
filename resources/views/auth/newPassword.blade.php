@extends('layouts/fullLayoutMaster')
@section('title', 'Forgot Password Page')

@section('content')
    <div class="auth-wrapper auth-cover">
        <div class="auth-inner row m-0">
            <!-- Brand logo-->
            <span class="brand-logo">
                <a href="" class="logo"> <img src="{{ asset('images/logo/VISER-X-New.png') }}" class="img-fluid"  style="height: 38px; padding-top: 5px;"/> </a>
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
                    <h2 class="card-title fw-bold mb-1">Reset Your Password</h2>
                    <p class="card-text mb-2">you can reset your password here </p>
                    <form class="auth-login-form mt-2" action="{{ route('newPassword') }}" method="POST">
                        @csrf
                        <div class="mb-1">
                                <!-- <div class="d-flex justify-content-between">
                                        <label class="form-label" for="login-password">Email</label>
                                        <a href="{{ route('login') }}">
                                            <small>Sign In</small>
                                        </a>
                                    </div> -->

                            <input type="hidden" name="user_id" value="{{ $user_id }}" />
                            <input type="hidden" name="otp" value="{{ $otp }}" />


                            <label class="form-label" for="login-password">Password</label>
                            <!-- <input class="form-control" id="password" type="text" name="password"
                                placeholder="********" aria-describedby="login-email" autofocus="" tabindex="1"
                               /> -->

                               <div class="input-group input-group-merge form-password-toggle">
                                <input class="form-control form-control-merge" id="login-password" type="password"
                                    name="password" placeholder="············" aria-describedby="login-password"
                                    tabindex="2" />
                                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                            </div>

                               <label class="form-label" for="login-password">Confirm Password</label>
                            <!-- <input class="form-control" id="password" type="text" name="password_confirmation"
                                placeholder="********" aria-describedby="login-email" autofocus="" tabindex="1"
                               /> -->
                               <div class="input-group input-group-merge form-password-toggle">
                                <input class="form-control form-control-merge" id="login-password" type="password"
                                    name="password_confirmation" placeholder="············" aria-describedby="login-password"
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
                        <button class="btn btn-primary w-100" tabindex="4">Update</button>
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
