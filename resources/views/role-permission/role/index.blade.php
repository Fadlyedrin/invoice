@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav')

    <div class="container-fluid py-4">
        <div class="row mt-4 mx-4">
            <div class="col-12">
                @if (session('success'))
                    <div class="alert alert-success text-white" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="card mb-4 ">
                    <div class="card-header">
                        <h4>Roles
                            @can('create roles')
                            <a href="{{ url('roles/create') }}" class="btn btn-primary float-end">Add Roles</a>
                            @endcan
                        </h4>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2 px-4">
                        <div class="table-responsive p-0">
                            <table class="table table-striped align-items-center mb-0" id="dataTableRole">
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
                                                <div class="d-flex px-3 py-1 justify-content-center align-items-center ">
                                                    @can('create roles')
                                                        <a href="{{ url('roles/' . $role->id . '/give-permissions') }}"
                                                            class="btn btn-secondary font-weight-bold mb-0 me-2 w-100 w-md-auto">Add
                                                            / Edit Role
                                                            Permission</a>
                                                    @endcan
                                                    @can('create roles')
                                                        <a href="{{ url('roles/' . $role->id . '/edit') }}"
                                                            class="btn btn-success font-weight-bold mb-0 me-2 w-100 w-md-auto">Edit</a>
                                                    @endcan

                                                    @can('delete roles')
                                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                                            onsubmit="return confirm('Are you sure?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-danger font-weight-bold mb-0 w-100 w-md-auto">Delete</button>
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
@push('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.js"></script>

    <script>
        new DataTable('#dataTableRole');
    </script>
@endpush
