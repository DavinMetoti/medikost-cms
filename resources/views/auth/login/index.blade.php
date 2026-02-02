@extends('layouts.app')

@section('content')
    <main class="main" id="top">
        <div class="container-fluid bg-body-tertiary dark__bg-gray-1200">
            <div class="bg-holder bg-auth-card-overlay" style="background-image:url(../../../assets/img/bg/37.png);"></div>
            <!--/.bg-holder-->
            <div class="row flex-center position-relative min-vh-100 g-0 py-5">
                <div class="col-11 col-sm-10 col-xl-8">
                    <div class="card border border-translucent auth-card">
                        <div class="card-body pe-md-0">
                            <div class="row align-items-center gx-0 gy-7">
                                <div class="col-auto bg-body-highlight dark__bg-gray-1100 rounded-3 position-relative overflow-hidden auth-title-box">
                                    <div class="bg-holder" style="background-image:url(../../../assets/img/bg/38.png);"></div>
                                    <!--/.bg-holder-->
                                    <div class="position-relative px-4 px-lg-7 pt-7 pb-7 pb-sm-5 text-center text-md-start pb-lg-7 pb-md-7">
                                        <h3 class="mb-3 text-body-emphasis fs-7">Medikost CMS</h3>
                                        <p class="text-body-tertiary">Sistem Manajemen Kost yang Mudah dan Efisien untuk Pengelolaan Kost Anda!</p>
                                        <ul class="list-unstyled mb-0 w-max-content w-md-auto">
                                            <li class="d-flex align-items-center"><span class="uil uil-check-circle text-success me-2"></span><span class="text-body-tertiary fw-semibold">Manajemen Kost Lengkap</span></li>
                                            <li class="d-flex align-items-center"><span class="uil uil-check-circle text-success me-2"></span><span class="text-body-tertiary fw-semibold">Booking Otomatis</span></li>
                                            <li class="d-flex align-items-center"><span class="uil uil-check-circle text-success me-2"></span><span class="text-body-tertiary fw-semibold">API untuk Frontend</span></li>
                                        </ul>
                                    </div>

                                    <div class="position-relative z-n1 mb-6 d-none d-md-block text-center mt-md-15"><img class="auth-title-box-img d-dark-none" src="../../../assets/img/spot-illustrations/auth.png" alt="" /><img class="auth-title-box-img d-light-none" src="../../../assets/img/spot-illustrations/auth-dark.png" alt="" /></div>
                                </div>
                                <div class="col mx-auto">
                                    <div class="auth-form-box">
                                        <div class="text-center mb-7">
                                            <a class="d-flex flex-center text-decoration-none mb-4" href="">
                                                <div class="d-flex align-items-center fw-bolder fs-3 d-inline-block"><img src="{{ secure_asset('assets/img/logos/medikost_logo.png') }}" alt="Medikost" width="58" /></div>
                                            </a>
                                            <h3 class="text-body-highlight text-success">Sign In</h3>
                                            <p class="text-body-tertiary text-success">Get access to your account</p>
                                            @if ($errors->has('login'))
                                                <div class="alert alert-outline-danger p-2" role="alert">
                                                    {{ $errors->first('login') }}
                                                </div>
                                            @endif

                                            <!-- Start: Login Form -->
                                            <form method="POST" action="{{ route('login.store') }}">
                                                @csrf <!-- CSRF Protection -->

                                                <!-- Email Input -->
                                                <div class="mb-3 text-start">
                                                    <label class="form-label" for="email">Email address</label>
                                                    <div class="form-icon-container">
                                                        <input
                                                            class="form-control form-icon-input @error('email') is-invalid @enderror"
                                                            id="email"
                                                            type="email"
                                                            name="email"
                                                            value="{{ old('email') }}"
                                                            placeholder="name@example.com"
                                                            required
                                                        />
                                                        <span class="fas fa-user text-body fs-9 form-icon"></span>
                                                    </div>
                                                    @error('email')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Password Input -->
                                                <div class="mb-3 text-start">
                                                    <label class="form-label" for="password">Password</label>
                                                    <div class="form-icon-container" data-password="data-password">
                                                        <input
                                                            class="form-control form-icon-input pe-6 @error('password') is-invalid @enderror"
                                                            id="password"
                                                            type="password"
                                                            name="password"
                                                            placeholder="Password"
                                                            required
                                                        />
                                                        <span class="fas fa-key text-body fs-9 form-icon"></span>
                                                        <button class="btn px-3 py-0 h-100 position-absolute top-0 end-0 fs-7 text-body-tertiary" data-password-toggle="data-password-toggle">
                                                            <span class="uil uil-eye show"></span>
                                                            <span class="uil uil-eye-slash hide"></span>
                                                        </button>
                                                    </div>
                                                    @error('password')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Remember Me Checkbox -->
                                                <div class="row flex-between-center mb-7">
                                                    <div class="col-auto">
                                                        <div class="form-check mb-0">
                                                            <input class="form-check-input" id="basic-checkbox" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} />
                                                            <label class="form-check-label mb-0" for="basic-checkbox">Remember me</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto"><a class="fs-9 fw-semibold text-success" href="forgot-password.html">Forgot Password?</a></div>
                                                </div>

                                                <!-- Sign In Button -->
                                                <button class="btn btn-success w-100 mb-3" type="submit">Sign In</button>
                                            </form>
                                            <!-- End: Login Form -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
