@extends('layouts.app' , ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    <div class="container-fluid py-4">
        <div class="row mt-4 mx-1 mx-sm-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h4 class="mb-2 mb-sm-0">Edit Roles</h4>
                        <a href="{{ url('roles') }}" class="btn btn-danger">Back</a>
                    </div>
                    <div class="card-body pt-0 pb-2 px-3 px-sm-5">
                        <form action="{{ url('roles/' . $role->id) }}" method="POST">
                            @csrf
                            @method('PUT') 
                            <div class="mb-3">
                                <label for="name" class="form-label">Roles Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $role->name }}" placeholder="Enter role name">
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary w-100 w-sm-auto">Save</button>
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
    @media (max-width: 576px) {
        .card-header h4 {
            font-size: 1.2rem;
        }

        .card-body .btn {
            font-size: 0.9rem;
        }

        .form-label {
            font-size: 0.95rem;
        }

        .form-control {
            font-size: 0.95rem;
        }
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
