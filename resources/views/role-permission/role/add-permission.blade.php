
@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav')
    <div class="container-fluid py-4">
        <div class="row mt-4 mx-1 mx-sm-4">
            <div class="col-12">
                @if (session('success'))
                    <div class="alert alert-success text-white" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h4 class="mb-2 mb-sm-0">Role: {{ $role->name }}</h4>
                        <a href="{{ url('roles') }}" class="btn btn-danger">Back</a>
                    </div>
                    <div class="card-body pt-0 pb-2 px-3 px-sm-5">
                        <form action="{{ url('roles/' . $role->id . '/give-permissions') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label mb-3">Permissions</label>
                                @error('permission')
                                    <div class="text-danger mb-2">{{ $message }}</div>
                                @enderror

                                @php
                                    // Group permissions by category
                                    $groupedPermissions = $permissions->groupBy(function ($permission) {
                                        $parts = explode(' ', $permission->name);
                                        return end($parts);
                                    });
                                @endphp

                                @foreach ($groupedPermissions as $category => $perms)
                                    <div class="card mb-3">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 text-capitalize">{{ $category }}</h6>
                                        </div>
                                        <div class="card-body pt-2">
                                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-2">
                                                @foreach ($perms->sortBy('name') as $permission)
                                                    <div class="col">
                                                        <div class="form-check">
                                                            <input type="checkbox" name="permission[]"
                                                                value="{{ $permission->name }}" class="form-check-input"
                                                                id="{{ $permission->name }}"
                                                                {{ in_array($permission->name, $role->permissions->pluck('name')->toArray()) ? 'checked' : '' }} />
                                                            <label class="form-check-label" for="{{ $permission->name }}">
                                                                {{ $permission->name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary w-100 w-sm-auto">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
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
@endpush
