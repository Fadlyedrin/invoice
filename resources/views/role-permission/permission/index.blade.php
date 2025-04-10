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
                        <h4 class="mb-2 mb-md-0">Permissions</h4>
                        @can('create permissions')
                            <a href="{{ url('permissions/create') }}" class="btn btn-primary">Add Permission</a>
                        @endcan
                    </div>
                    <div class="card-body px-2 px-sm-4 pt-0 pb-2">
                        <!-- Mobile view - card style -->
                        <div class="d-block d-md-none">
                            @foreach ($permissions as $permission)
                                <div class="permission-card mb-3 p-3 border rounded shadow-sm">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div>
                                            <span class="text-xs text-secondary">Permission #{{ $loop->iteration }}</span>
                                            <h5 class="mb-0 mt-1">{{ $permission->name }}</h5>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2 mt-3">
                                        @can('update permissions')
                                            <a href="{{ url('permissions/' . $permission->id . '/edit') }}" class="btn btn-success btn-sm flex-grow-1">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </a>
                                        @endcan
                                        @can('delete permissions')
                                            <button class="btn btn-danger btn-sm flex-grow-1" data-bs-toggle="modal" data-bs-target="#modalDelete{{ $permission->id }}">
                                                <i class="fas fa-trash me-1"></i> Delete
                                            </button>
                                        @endcan
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Desktop view - table -->
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-striped align-items-center mb-0" id="dataTablePermission">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">No.</th>
                                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Name</th>
                                        <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permissions as $permission)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $loop->iteration }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $permission->name }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-end">
                                                <div class="d-flex px-3 py-1 justify-content-center align-items-center gap-2">
                                                    @can('update permissions')
                                                        <a href="{{ url('permissions/' . $permission->id . '/edit') }}" class="btn btn-success btn-sm">
                                                            Edit
                                                        </a>
                                                    @endcan
                                                    @can('delete permissions')
                                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalDelete{{ $permission->id }}">
                                                            Delete
                                                        </button>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('role-permission.permission.delete-modal')
@endsection

@push('css')
    <style>
        /* Custom styles for mobile view */
        .permission-card {
            background-color: #fff;
            transition: all 0.2s ease;
        }
        
        .permission-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 12px rgba(0, 0, 0, 0.1) !important;
        }
        
        /* Adjust spacing for mobile */
        @media (max-width: 767px) {
            .container-fluid {
                padding-left: 0.1rem;
                padding-right: 0.1rem;
            }
            
            .card-header {
                padding: 1rem;
            }
            
            .btn {
                font-size: 0.875rem;
            }
            
            .card-body {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }
        }
    </style>
@endpush

@push('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    
    <script>
        new DataTable('#dataTablePermission');
    </script>
@endpush