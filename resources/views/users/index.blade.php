@extends('layouts/contentLayoutMaster')

@section('title', 'User List')

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

               @if (Auth::check() && Auth::user()->role === 'admin')
                <div class="card-body">
                    <form action="#" method="get">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#createUser">Add User</button>
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
                                <th>Email</th>
                                <th>Role</th>
                                <th>Phone Number</th>
                                <th>
                                    <a href="{{ route('users', array_merge(request()->except('page'), [
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
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <span class="fw-bold">
                                            {{ $user->name }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $user->email }}
                                    </td>
                                    <td>
                                        {{ $user->userRole?->name }}
                                    </td>
                                    <td>
                                        <span>
                                            {{ $user->phone }}
                                        </span>
                                    </td>

                                    <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y H:ia') }}</td>
                                    <td>

                                        {{-- <a class="" href="#">
                                            <i data-feather="eye" class="me-50"></i>
                                        </a> --}}

                                        <a href="#" class="edit-user" data-bs-toggle="modal"
                                            data-bs-target="#editUser" data-user-id="{{ $user->id }}">
                                            <i data-feather="edit-2" class="me-50"></i>
                                        </a>
                                        @if (Auth::check() && Auth::user()->role === 'admin')
                                        <form id="deleteForm" method="POST" action="{{ route('delete-user') }}" class="d-inline">
                                            @method('DELETE')
                                            @csrf
                                            <input type="text" name="user_id" id="delete-user-id" hidden>
                                            <button type="button" class="btn-link" style="border: none; background: none; padding: 0; margin: 0;"
                                               onclick="confirmDelete({{ $user->id }})">
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
                                        style="pointer-events: {{ $users->currentPage() == 1 ? 'none' : '' }}"
                                        href="{{ $users->url($users->currentPage() - 1) }}"></a>
                                </li>
                                @for ($i = 1; $i <= $users->lastPage(); $i++)
                                    <li class="page-item {{ $i == $users->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="page-item next" disabled><a class="page-link"
                                        style="pointer-events: {{ $users->currentPage() == $users->lastPage() ? 'none' : '' }}"
                                        href="{{ $users->url($users->currentPage() + 1) }}"></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">Add new user</h1>
                    </div>

                    <div class="card-body">
                        <form class="form form-horizontal" action="{{ route('create-user') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3">
                                        <label class="col-form-label" for="image">Profile Image</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="file" id="image" class="form-control" name="image" accept="image/*">
                                        @error('image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

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
                                            <label class="col-form-label" for="email-id">Email<span style="color: red"> * </span></label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="email" id="email" class="form-control" name="email"
                                                placeholder="Email" value="{{ old('email') }}" required/>
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="phn-id">Phone Number<span style="color: red"> * </span></label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="number" id="phn" class="form-control" name="phone"
                                                placeholder="phone number" value="{{ old('phone') }}" required/>
                                            @error('number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="password">Password<span style="color: red"> * </span></label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-merge form-password-toggle">
                                                <input class="form-control form-control-merge" id="login-password"
                                                    type="password" name="password" placeholder="············"
                                                    aria-describedby="login-password" tabindex="2" required autocomplete="new-password"/>
                                                <span class="input-group-text cursor-pointer"><i
                                                        data-feather="eye"></i></span>
                                            </div>
                                            @error('password')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <div id="login-password-validation"></div>
                                            <button type="button" class="btn btn-secondary mt-2" id="generate-password">Generate Strong Password</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="role">Role<span style="color: red"> * </span></label>
                                        </div>
                                        <div class="col-sm-9">
                                            <select name="role_id" id="role" class="form-select" required>
                                                <option value="" disabled selected>Select a Role</option>
                                                @foreach($userRoles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('role_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
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

    <div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">Update User</h1>
                    </div>

                    <div class="card-body">
                        <form class="form form-horizontal" id="edit-user-form"  action="" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3">
                                        <label class="col-form-label" for="user-image">Profile Image</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="file" id="user-image" class="form-control" name="image" accept="image/*" />
                                        @error('image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror

                                        <!-- Optional: Show Current Image Preview -->
                                        <div class="mt-2">
                                            <img id="current-image-preview" src="" alt="User Image" width="100" class="rounded" />
                                        </div>
                                    </div>
                                </div>
                            </div>

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
                                            <label class="col-form-label" for="email-id">Email</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="email" id="email-id" class="form-control" name="email"
                                                placeholder="Email"  />
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="phn-id">Phone Number</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="number" id="phn-id" class="form-control" name="phone"
                                                placeholder="phone number" />
                                            @error('number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="password">New Password</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-merge form-password-toggle">
                                                <input class="form-control form-control-merge" id="login-password-edit"
                                                    type="password" name="password" placeholder="············"
                                                    aria-describedby="login-password-edit" tabindex="2" autocomplete="new-password" />
                                                <span class="input-group-text cursor-pointer"><i
                                                        data-feather="eye"></i></span>
                                            </div>
                                            @error('password')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <div id="login-password-validation-edit"></div>
                                            <button type="button" class="btn btn-secondary mt-2" id="generate-password-edit">Generate Strong Password</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="role">Role<span style="color: red"> * </span></label>
                                        </div>
                                        <div class="col-sm-9">
                                            <select name="role_id" id="role_edit" class="form-select" required>
                                                <option value="" disabled selected>Select a Role</option>
                                                @foreach($userRoles as $role)
                                                    <option value="{{ $role->id }}">
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
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
            const editLinks = document.querySelectorAll('.edit-user');
            const editForm = document.getElementById('edit-user-form');

            editLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const userId = this.dataset.userId;


                    editForm.action = `/users/${userId}`;

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

                    axios.get(`/users/${userId}/edit`)
                        .then(response => {

                            const userData = response.data;

                            document.getElementById('first-name').value = userData.name;
                            document.getElementById('email-id').value = userData.email;
                            document.getElementById('phn-id').value = userData.phone;

                            const roleSelect = document.getElementById('role_edit');

                            // Ensure the role is selected based on userData.role_id
                            for (let option of roleSelect.options) {
                                console.log(option.value, userData.role_id)
                                if (option.value == userData.role_id) {
                                    option.selected = true;
                                    break;
                                }
                            }
                            const imagePreview = document.getElementById('current-image-preview');
                            if (userData.image) {
                                imagePreview.src = `/storage/${userData.image}`;
                            } else {
                                imagePreview.src = '/images/portrait/small/av2.jpg';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching user data', error);
                        });
                });
            });

            editForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default form submit

                // Show SweetAlert confirmation
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to update this user!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, update it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If confirmed, submit the form
                        e.target.submit();
                    }
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
        function confirmDelete(userId) {

            console.log(userId);
            document.getElementById('delete-user-id').value = userId;
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

<script>
    document.addEventListener("DOMContentLoaded", function () {
    function setupPasswordValidation(passwordInputId, validationContainerId, submitButtonSelector, generateButtonId, type) {
        const passwordInput = document.getElementById(passwordInputId);
        const passwordValidationContainer = document.getElementById(validationContainerId);
        const submitButton = document.querySelector(submitButtonSelector);
        const passwordRequirements = document.createElement("ul");

        if (type === 'create') {
            submitButton.disabled = true;
        }

        passwordRequirements.innerHTML = `
            <li id="${passwordInputId}-length" style="color:red;">At least 8 characters</li>
            <li id="${passwordInputId}-uppercase" style="color:red;">At least one uppercase letter</li>
            <li id="${passwordInputId}-lowercase" style="color:red;">At least one lowercase letter</li>
            <li id="${passwordInputId}-number" style="color:red;">At least one number</li>
            <li id="${passwordInputId}-special" style="color:red;">At least one special character (@$!%*#?&)</li>
        `;
        passwordValidationContainer.appendChild(passwordRequirements);

        passwordInput.addEventListener("input", function () {
            const value = passwordInput.value;
            if (type === 'edit') {
                if (value === '') {
                    submitButton.disabled = false;
                    document.getElementById("length").style.color = "red";
                    document.getElementById("uppercase").style.color = "red";
                    document.getElementById("lowercase").style.color = "red";
                    document.getElementById("number").style.color = "red";
                    document.getElementById("special").style.color = "red";
                    return; // Exit the function early
                }
            }

            document.getElementById(`${passwordInputId}-length`).style.color = value.length >= 8 ? "green" : "red";
            document.getElementById(`${passwordInputId}-uppercase`).style.color = /[A-Z]/.test(value) ? "green" : "red";
            document.getElementById(`${passwordInputId}-lowercase`).style.color = /[a-z]/.test(value) ? "green" : "red";
            document.getElementById(`${passwordInputId}-number`).style.color = /[0-9]/.test(value) ? "green" : "red";
            document.getElementById(`${passwordInputId}-special`).style.color = /[@$!%*#?&]/.test(value) ? "green" : "red";

            const isValid = value.length >= 8 && /[A-Z]/.test(value) && /[a-z]/.test(value) &&
                /[0-9]/.test(value) && /[@$!%*#?&]/.test(value);

            submitButton.disabled = !isValid;
        });

        document.getElementById(generateButtonId).addEventListener("click", function () {
            const uppercaseChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            const lowercaseChars = "abcdefghijklmnopqrstuvwxyz";
            const numberChars = "0123456789";
            const specialChars = "@$!%*#?&";
            const allChars = uppercaseChars + lowercaseChars + numberChars + specialChars;

            let password = "";

            password += uppercaseChars[Math.floor(Math.random() * uppercaseChars.length)];
            password += lowercaseChars[Math.floor(Math.random() * lowercaseChars.length)];
            password += numberChars[Math.floor(Math.random() * numberChars.length)];
            password += specialChars[Math.floor(Math.random() * specialChars.length)];

            for (let i = 0; i < 8; i++) {
                password += allChars[Math.floor(Math.random() * allChars.length)];
            }

            password = password.split('').sort(() => Math.random() - 0.5).join('');

            passwordInput.value = password;
            passwordInput.dispatchEvent(new Event("input"));
        });
    }

    setupPasswordValidation("login-password", "login-password-validation", "#createUser button[type='submit']", "generate-password", "create");
    setupPasswordValidation("login-password-edit", "login-password-validation-edit", "#edit-user-form button[type='submit']", "generate-password-edit", "edit");
});
</script>

<script>
    document.getElementById('user-image').onchange = function (e) {
        let [file] = e.target.files;
        if (file) {
            document.getElementById('current-image-preview').src = URL.createObjectURL(file);
        }
    };
</script>

@endsection
