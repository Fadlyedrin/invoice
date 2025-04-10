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
                        <h4 class="mb-2 mb-md-0">Roles</h4>
                        @can('create roles')
                            <a href="{{ url('roles/create') }}" class="btn btn-primary">Add Roles</a>
                        @endcan
                    </div>
                    <div class="card-body px-2 px-sm-4 pt-0 pb-2">
                        <!-- Mobile view - card style -->
                        <div class="d-block d-md-none">
                            @foreach ($roles as $role)
                                <div class="role-card mb-3 p-3 border rounded shadow-sm">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div>
                                            
                                            <span class="text-xs text-secondary">Role #{{ $loop->iteration }}</span>
                                            <h5 class="mb-0 mt-1">{{ $role->name }}</h5>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column gap-2 mt-3">
                                        @can('create roles')
                                            <a href="{{ url('roles/' . $role->id . '/give-permissions') }}" class="btn btn-secondary btn-sm w-100">
                                                <i class="fas fa-key me-1"></i> Add/Edit Permissions
                                            </a>
                                            <a href="{{ url('roles/' . $role->id . '/edit') }}" class="btn btn-success btn-sm w-100">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </a>
                                        @endcan
                                        @can('delete roles')
                                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" class="w-100">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                                    <i class="fas fa-trash me-1"></i> Delete
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Desktop view - table -->
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-striped align-items-center mb-0" id="dataTableRole">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">No.</th>
                                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Name</th>
                                        <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $role)
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
                                                        <h6 class="mb-0 text-sm">{{ $role->name }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-end">
                                                <div class="d-flex px-3 py-1 justify-content-center align-items-center gap-2">
                                                    @can('create roles')
                                                        <a href="{{ url('roles/' . $role->id . '/give-permissions') }}" class="btn btn-secondary btn-sm">
                                                            Add/Edit Permissions
                                                        </a>
                                                        <a href="{{ url('roles/' . $role->id . '/edit') }}" class="btn btn-success btn-sm">
                                                            Edit
                                                        </a>
                                                    @endcan
                                                    @can('delete roles')
                                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                        </form>
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
@endsection

@push('css')
    <style>
        /* Custom styles for mobile view */
        .role-card {
            background-color: #fff;
            transition: all 0.2s ease;
        }
        
        .role-card:hover {
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
        new DataTable('#dataTableRole');
    </script>
@endpush