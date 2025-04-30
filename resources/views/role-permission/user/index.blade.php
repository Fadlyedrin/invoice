@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')

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
                    <h4 class="mb-2 mb-md-0">Users</h4>
                    @can('create users')
                        <a href="{{ url('users/create') }}" class="btn btn-primary">Add User</a>
                    @endcan
                </div>
                <div class="card-body px-2 px-sm-4 pt-0 pb-2">
                    {{-- Mobile view --}}
                    <div class="d-block d-md-none">
                        @foreach ($users as $user)
                            <div class="role-card mb-3 p-3 border rounded shadow-sm">
                                <div class="mb-2">
                                    <span class="text-xs text-secondary">{{ $loop->iteration }}</span>
                                    <h5 class="mb-1 mt-1">{{ $user->username }}</h5>
                                    <p class="mb-1 text-sm"><strong>Email:</strong> {{ $user->email }}</p>
                                    <p class="mb-1 text-sm">
                                        <strong>Roles:</strong>
                                        @if ($user->roles->isNotEmpty())
                                            @foreach ($user->getRoleNames() as $role)
                                                <span class="badge bg-primary">{{ $role }}</span>
                                            @endforeach
                                        @else
                                            <span class="badge bg-secondary">No role assigned</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="d-flex flex-column gap-2 mt-3">
                                    @can('update users')
                                        <a href="{{ url('users/' . $user->id . '/edit') }}"
                                           class="btn btn-success btn-sm w-100">Edit</a>
                                    @endcan
                                    @can('delete users')
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                              onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm w-100">Delete</button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Desktop view --}}
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-striped align-items-center mb-0" id="dataTableUser">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if ($user->roles->isNotEmpty())
                                                @foreach ($user->getRoleNames() as $role)
                                                    <span class="badge bg-primary">{{ $role }}</span>
                                                @endforeach
                                            @else
                                                <span class="badge bg-secondary">No role assigned</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                @can('update users')
                                                    <a href="{{ url('users/' . $user->id . '/edit') }}"
                                                       class="btn btn-success btn-sm">Edit</a>
                                                @endcan
                                                @can('delete users')
                                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                          onsubmit="return confirm('Are you sure?');">
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
        .role-card {
            background-color: #fff;
            transition: all 0.2s ease;
        }

        .role-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 12px rgba(0, 0, 0, 0.1) !important;
        }

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

    <script>
        new DataTable('#dataTableUser');
    </script>
@endpush
