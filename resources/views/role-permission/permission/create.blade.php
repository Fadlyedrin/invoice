@extends('layouts.app' , ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav')
    <div class="container-fluid py-4">
        <div class="row mt-4 mx-4">
        <div class="col-12">
            {{-- <div class="alert alert-light" role="alert">
                This feature is available in <strong>Argon Dashboard 2 Pro Laravel</strong>. Check it
                <strong>
                    <a href="https://www.creative-tim.com/product/argon-dashboard-pro-laravel" target="_blank">
                        here
                    </a>
                </strong>
            </div> --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Create Permission
                        <a href="{{ url('permissions') }}" class="btn btn-danger float-end">Back</a>
                    </h4>
                </div>
                <div class="card-body px-0 pt-0 pb-2 mx-5">
                    <form action="{{ url('permissions') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="">Permission Name</label>
                            <input type="text" name="name" class="form-control" />
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
