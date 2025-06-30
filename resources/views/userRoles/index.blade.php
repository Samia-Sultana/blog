@extends('layouts/contentLayoutMaster')

@section('title', 'Role List')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('content')

@if(Session::has('success'))
    <div id="success-alert" class="alert alert-success" style="padding: 15px;">
        {{ Session::get('success') }}
    </div>
@endif

    <div class="row" id="table-hover-row">
        <div class="col-12">
            <div class="card">

               @if (Auth::check())
                <div class="card-body">
                    <form action="#" method="get">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#createRole">Add Role</button>
                        </div>
                    </form>
                </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>
                                    <a href="{{ route('roles', array_merge(request()->except('page'), [
                                        'sort_by' => 'created_at',
                                        'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'created_at' ? 'desc' : 'asc'
                                    ])) }}" class="text-decoration-none text-dark">
                                        Created At
                                        @php
                                            $sortBy = request('sort_by', 'created_at');
                                            $sortOrder = request('sort_order', 'desc');
                                        @endphp
                                        @if($sortBy === 'created_at')
                                            {!! $sortOrder === 'asc' ? '▲' : '▼' !!}
                                        @endif
                                    </a>
                                </th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($userRoles as $userRole)
                                <tr>
                                    <td>{{ ($userRoles->currentPage() - 1) * $userRoles->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <span class="fw-bold">
                                            {{ $userRole->name }}
                                        </span>
                                    </td>

                                    <td>{{ \Carbon\Carbon::parse($userRole->created_at)->format('d M Y H:ia') }}</td>
                                    <td>

                                        {{-- <a class="" href="#">
                                            <i data-feather="eye" class="me-50"></i>
                                        </a> --}}

                                        <a href="#" class="edit-userRole" data-bs-toggle="modal"
                                            data-bs-target="#editUserRole" data-user-id="{{ $userRole->id }}">
                                            <i data-feather="edit-2" class="me-50"></i>
                                        </a>
                                        @if (Auth::check())
                                        <form id="deleteForm" method="POST" action="{{ route('delete-role') }}" class="d-inline">
                                            @method('DELETE')
                                            @csrf
                                            <input type="text" name="role_id" id="delete-role-id" hidden>
                                            <button type="button" class="btn-link" style="border: none; background: none; padding: 0; margin: 0;"
                                               onclick="confirmDelete({{ $userRole->id }})">
                                               <i data-feather="trash-2" class="me-50"></i>
                                            </button>
                                        </form>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mx-1 d-flex justify-content-end">
                        <nav aria-label="Page navigation">
                            <ul class="pagination mt-2">
                                <li class="page-item prev"><a class="page-link"
                                        style="pointer-events: {{ $userRoles->currentPage() == 1 ? 'none' : '' }}"
                                        href="{{ $userRoles->url($userRoles->currentPage() - 1) }}"></a>
                                </li>
                                @for ($i = 1; $i <= $userRoles->lastPage(); $i++)
                                    <li class="page-item {{ $i == $userRoles->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $userRoles->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="page-item next" disabled><a class="page-link"
                                        style="pointer-events: {{ $userRoles->currentPage() == $userRoles->lastPage() ? 'none' : '' }}"
                                        href="{{ $userRoles->url($userRoles->currentPage() + 1) }}"></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createRole" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">Add new role</h1>
                    </div>

                    <div class="card-body">
                        <form class="form form-horizontal" action="{{ route('create-role') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="first-name">Name<span style="color: red"> * </span></label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" id="name" class="form-control" name="name"
                                                placeholder="Name"  required/>
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="modules">Modules</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="row">
                                                @foreach($modules as $index => $module)
                                                    @if($index % 2 == 0 && $index != 0)
                                                        </div><div class="row"> <!-- Start a new row after every two modules -->
                                                    @endif
                                                    <div class="col-6"> <!-- Each column takes up half of the width -->
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="modules[]" value="{{ $module->id }}" id="module-{{ $module->id }}"
                                                            {{ in_array($module->id, old('modules', [])) ? 'checked' : '' }} />
                                                            <label class="form-check-label" for="module-{{ $module->id }}">
                                                                {{ ucwords(str_replace(['-', '.'], ' ', $module->name)) }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-9 offset-sm-3">
                                    @if (session('error'))
                                        <div class="text-danger">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                    <button type="submit" class="btn btn-primary me-1">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUserRole" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">Update Role</h1>
                    </div>

                    <div class="card-body">
                        <form class="form form-horizontal" id="edit-userRole-form" action="" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="first-name">Name</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" id="first-name" class="form-control" name="name"
                                                placeholder="Name" />
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="modules">Modules</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="row">
                                                @foreach($modules as $index => $module)
                                                    @if($index % 2 == 0 && $index != 0)
                                                        </div><div class="row"> <!-- Start a new row after every two modules -->
                                                    @endif
                                                    <div class="col-6"> <!-- Each column takes up half of the width -->
                                                        <div class="form-check">
                                                            <input class="form-check-input module-{{ $module->id }}" type="checkbox"
                                                                   name="modules[]" value="{{ $module->id }}" id="module-edit-{{ $module->id }}"
                                                                   {{ in_array($module->id, old('modules', [])) ? 'checked' : '' }} />
                                                            <label class="form-check-label" for="module-edit-{{ $module->id }}">
                                                                {{ ucwords(str_replace(['-', '.'], ' ', $module->name)) }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-9 offset-sm-3">
                                    @if (session('error'))
                                        <div class="text-danger">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                    <button type="submit" class="btn btn-primary me-1">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Hoverable rows end -->

@endsection

@section('vendor-script')
    <!-- vendor js files -->
    <script src="{{ asset(mix('vendors/js/pagination/jquery.bootpag.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pagination/jquery.twbsPagination.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/pagination/components-pagination.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editLinks = document.querySelectorAll('.edit-userRole');

            editLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const userId = this.dataset.userId;

                    const editForm = document.getElementById('edit-userRole-form');
                    editForm.reset();
                    editForm.action = `/roles/${userId}`;

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

                    axios.get(`/roles/${userId}/edit`)
                        .then(response => {

                            const {userRole, modules} = response.data;

                            document.getElementById('first-name').value = userRole.name;
                            modules.forEach(module => {
                                $('.module-' + module.id).prop('checked', true);
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching user data', error);
                        });
                });
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function(){
            setTimeout(function(){
                $("#success-alert").alert('close');
            }, 3000);
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        function confirmDelete(roleId) {

            document.getElementById('delete-role-id').value = roleId;
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                submitForm();
            }
        });
    }

    function submitForm() {
        document.getElementById('deleteForm').submit();
    }
    </script>



@endsection
