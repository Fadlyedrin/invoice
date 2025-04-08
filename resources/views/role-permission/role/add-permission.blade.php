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
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Role : {{ $role->name }}
                            <a href="{{ url('roles') }}" class="btn btn-danger float-end">Back</a>
                        </h4>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2 mx-5">
                        <form action="{{ url('roles/' . $role->id . '/give-permissions') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                @error('permission')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <label for="" class="form-label mb-3">Permissions</label>
                                <div class="row">
                                    
                                    @foreach ($permissions as $permission)
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input type="checkbox" name="permission[]" value="{{ $permission->name }}"
                                                    class="form-check-input" {{ in_array($permission->name, $role->permissions->pluck('name')->toArray()) ? 'checked' : '' }}/>
                                                <label class="form-check-label" for="{{ $permission->name }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
