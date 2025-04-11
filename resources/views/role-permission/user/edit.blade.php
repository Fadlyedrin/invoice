@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Your Profile'])

    <div id="alert">
        @include('components.alert')
    </div>
    <div class="container-fluid py-4 mt-4">
        <div class="row">
            <div class="col-md-12">
                @if (session('success'))
                    <div class="alert alert-success text-white" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="card">
                    <form role="form" method="POST" action="{{ url('users/' . $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Edit User</p>
                                <a href="{{ url('users') }}" class="btn btn-primary btn-sm ms-auto">Back</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <p class="text-uppercase text-sm">User Information</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Username</label>
                                        <input class="form-control" type="text" value="{{ $user->username }}"
                                            name="username" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Email address</label>
                                        <input class="form-control" type="email" value="{{ $user->email }}"
                                            name="email" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group position-relative">
                                        <label for="example-text-input" class="form-control-label">Password</label>
                                        <input id="password" class="form-control" type="password" name="password">

                                        <!-- Ikon mata -->
                                        <span onclick="togglePassword()"
                                            style="position: absolute; top: 38px; right: 15px; cursor: pointer;">
                                            <i id="togglePasswordIcon" class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Roles</label>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle w-100" type="button"
                                                id="dropdownRoles" data-bs-toggle="dropdown" aria-expanded="false">
                                                Select Roles
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownRoles">
                                                @foreach ($roles as $role)
                                                    <li>
                                                        <label class="dropdown-item">
                                                            <input type="checkbox" name="roles[]"
                                                                value="{{ $role }}"
                                                                {{ in_array($role, $userRoles) ? 'checked' : '' }}
                                                                class="form-check-input">
                                                            {{ $role }}
                                                        </label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @error('roles')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                            </div>

                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="branch-type">Pilih Jenis</label>
                                    <select id="branch-type" name="branch_type" class="form-control">
                                        <option value="Pusat" {{ old('branch_type') == 'Pusat' ? 'selected' : '' }}>Pusat</option>
                                        <option value="Cabang" {{ old('branch_type') == 'Cabang' ? 'selected' : '' }}>Cabang</option>
                                    </select>
                                </div>
                            </div> --}}

                            <!-- Form untuk Cabang -->
                            <div id="branch-fields">
                                <hr class="horizontal dark">
                                <p class="text-uppercase text-sm">Contact Information</p>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Address</label>
                                            <input class="form-control" type="text" name="address">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">City</label>
                                            <input class="form-control" type="text" name="city">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">No.telp</label>
                                            <input class="form-control" type="number" name="phone">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="horizontal dark">
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
<style>
    @media (max-width: 767px) {
        .container-fluid {
            padding-left: 0.1rem;
            padding-right: 0.1rem;
        }

        .row.mx-1 {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .card-body {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }

        .invoice-card {
            margin-left: 0;
            margin-right: 0;
            width: 100%;
        }

        .card-header {
            padding: 1rem;
            text-align: center;
        }

        .btn-sm {
            padding: 0.35rem 0.65rem;
            font-size: 0.875rem;
        }

        /* Add extra margin to buttons on mobile */
        .action-buttons .btn {
            margin-right: 8px;
        }

        .action-buttons .btn:last-child {
            margin-right: 0;
        }
    }
</style>
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
    </script>
@endpush
