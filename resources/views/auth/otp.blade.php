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
                    <h2 class="card-title fw-bold mb-1">Otp verification</h2>
                    <!-- <p class="card-text mb-2">Check your OTP email </p> -->
                    <form class="auth-login-form mt-2" action="{{ route('verifyOtp') }}" method="POST">
                        @csrf
                        <div class="mb-1">
                              <label class="form-label" for="otp">Check your  email and enter the OTP here</label>
                           
                            <input class="form-control" id="otp" type="number" name="otp"
                                placeholder="123456" aria-describedby="login-email" autofocus="" tabindex="1"
                                />
                            @error('otp')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    
                        @if (session('error'))
                            <div class="text-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <button class="btn btn-primary w-100" tabindex="4">Send</button>
                    </form>
                  
                </div>
            </div>
            <!-- /Login-->
        </div>
    </div>
@endsection
