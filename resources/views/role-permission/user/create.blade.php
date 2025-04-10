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
                    <form role="form" method="POST" action="{{ url('users') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Create User</p>
                                <a href="{{ url('users') }}" class="btn btn-primary btn-sm ms-auto">Back</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <p class="text-uppercase text-sm">User Information</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Username*</label>
                                        <input class="form-control" type="text" name="username" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Email address*</label>
                                        <input class="form-control" type="email" name="email" required>
                                        @error('email')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Password*</label>
                                        <input class="form-control" type="password" name="password" required>
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
                                                                value="{{ $role }}" class="form-check-input">
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
                                <button type="submit" class="btn btn-primary">Save</button>
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

{{-- @push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let branchType = document.getElementById("branch-type");
            let branchFields = document.getElementById("branch-fields");

            function toggleBranchFields() {
                if (branchType.value === "Cabang") {
                    branchFields.style.display = "block"; // Tampilkan form
                } else {
                    branchFields.style.display = "none"; // Sembunyikan form
                }
            }

            // Jalankan saat halaman dimuat
            toggleBranchFields();

            // Jalankan saat dropdown diubah
            branchType.addEventListener("change", toggleBranchFields);
        });
    </script>
@endpush --}}
