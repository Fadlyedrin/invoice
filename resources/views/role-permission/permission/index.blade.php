@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav')

    {{-- <div class="row mt-4 mx-4">
        <div class="col-12">

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <div class="card mt-3">
                <div class="card-header">
                    <h4>Permissions
                        @can('create permission')
                        <a href="{{ url('permissions/create') }}" class="btn btn-primary float-end">Add Permission</a>
                        @endcan
                    </h4>
                </div>
                <div class="card-body">

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th width="40%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                                @foreach ($permissions as $permission)
                                <tr>
                                    <td>{{ $permission->id }}</td>
                                    <td>{{ $permission->name }}</td>
                                    <td>
                                        @can('update permission')
                                        <a href="{{ url('permissions/'.$permission->id.'/edit') }}" class="btn btn-success">Edit</a>
                                        @endcan

                                        @can('delete permission')
                                        <a href="{{ url('permissions/'.$permission->id.'/delete') }}" class="btn btn-danger mx-2">Delete</a>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div> --}}

    <div class="container-fluid py-4">
        <div class="row mt-4 mx-4">
            <div class="col-12">
                @if (session('success'))
                    <div class="alert alert-success text-white" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Permissions
                            @can('create permissions')
                            <a href="{{ url('permissions/create') }}" class="btn btn-primary float-end">Add Permission</a>
                            @endcan
                        </h4>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2 px-4">
                        <div class="table-responsive p-0">
                            <table class="table table-striped align-items-center mb-0 overflow-hidden"
                                id="dataTablePermission">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">No.
                                        </th>
                                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Name
                                        </th>
                                        {{-- <th
                                            class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7 ps-2">
                                            Role
                                        </th> --}}
                                        <th
                                            class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                            Action</th>
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
                                                <div class="d-flex px-3 py-1 justify-content-center align-items-center ">
                                                    @can('update permissions')
                                                        <a href="{{ url('permissions/' . $permission->id . '/edit') }}"
                                                            class="btn btn-success font-weight-bold mb-0 me-2">Edit</a>
                                                    @endcan
                                                    @can('delete permissions')
                                                        <button class="btn btn-danger font-weight-bold mb-0"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalDelete{{ $permission->id }}">Delete</button>
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
@push('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.js"></script>

    <script>
        new DataTable('#dataTablePermission');
    </script>
@endpush
