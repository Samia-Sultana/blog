@extends('layouts/contentLayoutMaster')

@section('title', 'User List')

@section('vendor-style')
<!-- vendor css files -->
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('content')

<div>

@if(Session::has('success'))
<div id="success-alert" class="alert alert-success" style="padding: 15px;">
    {{ Session::get('success') }}
</div>
@endif
    <div class="card" style="width: fit-content; padding: 40px">


        <div class="card-body text-center">
            <img class="round"
                src="{{ isset($user->image) ? asset("storage/".$user->image) : asset('images/portrait/small/av2.jpg') }}"
                alt="avatar" height="40" width="40">
            <h5 class="card-title">{{ $user->name }}</h5>
            <p class="card-text">
                <strong>Email:</strong> {{ $user->email }} <br>
                <strong>Phone:</strong> {{ $user->phone }}
            </p>
            <a href="#" class="edit-user" data-bs-toggle="modal"
                                            data-bs-target="#editUser" data-user-id="{{ $user->id }}">
                                          <span class="btn btn-primary">Update</span>
                                        </a>
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
                                            <input required type="email" id="email-id" class="form-control" name="email"
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
                                            <input required type="number" id="phn-id" class="form-control" name="phone"
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
                                                <input class="form-control form-control-merge" id="login-password"
                                                    type="password" name="password" placeholder="············"
                                                    aria-describedby="login-password" tabindex="2" autocomplete="new-password" />
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
    $(document).ready(function() {
        setTimeout(function() {
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
    const passwordInput = document.getElementById("login-password");
    const passwordValidationContainer = document.getElementById("login-password-validation");
    const submitButton = document.querySelector("#editUser button[type='submit']");
    const passwordRequirements = document.createElement("ul");

    passwordRequirements.innerHTML = `
        <li id="length" style="color:red;">At least 8 characters</li>
        <li id="uppercase" style="color:red;">At least one uppercase letter</li>
        <li id="lowercase" style="color:red;">At least one lowercase letter</li>
        <li id="number" style="color:red;">At least one number</li>
        <li id="special" style="color:red;">At least one special character (@$!%*#?&)</li>
    `;
    passwordValidationContainer.appendChild(passwordRequirements);

    passwordInput.addEventListener("input", function () {
        const value = passwordInput.value;
        if (value === '') {
                submitButton.disabled = false;
                document.getElementById("length").style.color = "red";
                document.getElementById("uppercase").style.color = "red";
                document.getElementById("lowercase").style.color = "red";
                document.getElementById("number").style.color = "red";
                document.getElementById("special").style.color = "red";
                return; // Exit the function early
        }
        document.getElementById("length").style.color = value.length >= 8 ? "green" : "red";
        document.getElementById("uppercase").style.color = /[A-Z]/.test(value) ? "green" : "red";
        document.getElementById("lowercase").style.color = /[a-z]/.test(value) ? "green" : "red";
        document.getElementById("number").style.color = /[0-9]/.test(value) ? "green" : "red";
        document.getElementById("special").style.color = /[@$!%*#?&]/.test(value) ? "green" : "red";

        const isValid = value.length >= 8 && /[A-Z]/.test(value) && /[a-z]/.test(value) &&
            /[0-9]/.test(value) && /[@$!%*#?&]/.test(value);

        submitButton.disabled = !isValid;
    });
});

    </script>

    <script>
        document.getElementById("generate-password").addEventListener("click", function () {
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

            const passwordInput = document.getElementById("login-password");
            passwordInput.value = password;

            passwordInput.dispatchEvent(new Event("input"));
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
