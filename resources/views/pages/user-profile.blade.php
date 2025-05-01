@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1 font-weight-bold">
                            {{ auth()->user()->username }}
                        </h5>
                        <p class="mb-0 text-sm text-secondary">
                            <i class="fas fa-envelope me-1"></i> {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="alert" class="mt-3 mx-4">
        @include('components.alert')
    </div>
    
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form role="form" method="POST" action={{ route('profile.update') }} enctype="multipart/form-data">
                        @csrf
                        <div class="card-header pb-0 bg-gradient-primary">
                            <div class="d-flex align-items-center">
                                <p class="mb-0 text-white">Edit Profile</p>
                                <button type="submit" class="btn btn-light btn-sm ms-auto">
                                    <i class="fas fa-save me-1"></i> Save Changes
                                </button>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <!-- User Information Section -->
                            <div class="mb-4">
                                <h6 class="text-uppercase text-primary text-sm fw-bold">
                                    <i class="fas fa-user me-2"></i>User Information
                                </h6>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="username" class="form-control-label">Username</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                <input class="form-control" type="text" name="username" id="username"
                                                    value="{{ old('username', auth()->user()->username) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="form-control-label">Email address</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                <input class="form-control" type="email" name="email" id="email"
                                                    value="{{ old('email', auth()->user()->email) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="horizontal dark">
                            
                            <!-- Password Update Accordion -->
                            <div class="mb-4">
                                <div class="accordion" id="passwordAccordion">
                                    <div class="accordion-item border-0 bg-transparent">
                                        <h2 class="accordion-header" id="headingPassword">
                                            <button class="accordion-button collapsed bg-white shadow-sm rounded" type="button" 
                                                    data-bs-toggle="collapse" data-bs-target="#collapsePassword" 
                                                    aria-expanded="false" aria-controls="collapsePassword">
                                                <h6 class="text-uppercase text-primary text-sm fw-bold mb-0">
                                                    <i class="fas fa-lock me-2"></i>Change Password
                                                </h6>
                                            </button>
                                        </h2>
                                        <div id="collapsePassword" class="accordion-collapse collapse" 
                                            aria-labelledby="headingPassword" data-bs-parent="#passwordAccordion">
                                            <div class="accordion-body px-1 py-3">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="current_password" class="form-control-label">Current Password</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                <input class="form-control" type="password" name="current_password" id="current_password">
                                                                <span class="input-group-text bg-transparent border-start-0" style="cursor: pointer;" onclick="toggleCurrentPassword()">
                                                                    <i class="fas fa-eye" id="toggleCurrentPasswordIcon"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="password" class="form-control-label">New Password</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                                <input class="form-control" type="password" name="password" id="password">
                                                                <span class="input-group-text bg-transparent border-start-0" style="cursor: pointer;" onclick="togglePassword()">
                                                                    <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="password_confirmation" class="form-control-label">Confirm New Password</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-check-double"></i></span>
                                                                <input class="form-control" type="password" name="password_confirmation" id="password_confirmation">
                                                                <span class="input-group-text bg-transparent border-start-0" style="cursor: pointer;" onclick="toggleConfirmPassword()">
                                                                    <i class="fas fa-eye" id="toggleConfirmPasswordIcon"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <div class="alert alert-info py-2" role="alert">
                                                            <div class="d-flex">
                                                                <div>
                                                                    <i class="fas fa-info-circle me-2"></i>
                                                                </div>
                                                                <div>
                                                                    <small>Leave these fields empty if you don't want to change your password</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="horizontal dark">
                            
                            <!-- Contact Information Section -->
                            <div class="mb-4">
                                <h6 class="text-uppercase text-primary text-sm fw-bold">
                                    <i class="fas fa-address-card me-2"></i>Contact Information
                                </h6>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="address" class="form-control-label">Address</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                <input class="form-control" type="text" name="address" id="address"
                                                    value="{{ old('address', auth()->user()->address) }}">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="city" class="form-control-label">City</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-city"></i></span>
                                                <input class="form-control" type="text" name="city" id="city"
                                                    value="{{ old('city', auth()->user()->city) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone" class="form-control-label">Phone Number</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                <input class="form-control" type="text" name="phone" id="phone"
                                                    value="{{ old('phone', auth()->user()->phone) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection

@push('js')
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const icon = document.getElementById("togglePasswordIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }

        function toggleCurrentPassword() {
            const passwordInput = document.getElementById("current_password");
            const icon = document.getElementById("toggleCurrentPasswordIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }

        function toggleConfirmPassword() {
            const passwordInput = document.getElementById("password_confirmation");
            const icon = document.getElementById("toggleConfirmPasswordIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
        
        // Clear password fields when accordion is closed
        document.getElementById('collapsePassword').addEventListener('hidden.bs.collapse', function () {
            document.getElementById('current_password').value = '';
            document.getElementById('password').value = '';
            document.getElementById('password_confirmation').value = '';
        });
    </script>
@endpush