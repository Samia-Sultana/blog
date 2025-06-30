@extends('layouts/contentLayoutMaster')

@section('title', 'Blog Category')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/katex.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/monokai-sublime.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.snow.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.bubble.css')) }}">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inconsolata&family=Roboto+Slab&family=Slabo+27px&family=Sofia&family=Ubuntu+Mono&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/file-uploaders/dropzone.min.css')) }}">
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-quill-editor.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-file-uploader.css')) }}">
@endsection

@section('content')

    @if (Session::has('success'))
        <div id="success-alert" class="alert alert-success" style="padding: 15px;">
            {{ Session::get('success') }}
        </div>
    @endif


    @if (session('error'))
        <div id="error-alert" class="alert alert-danger" style="padding: 15px;">
            {{ session('error') }}
        </div>
    @endif

    <div class="row" id="table-hover-row">
        <div class="col-12">
            <div class="card">

                <div class="card-body">
                    <form action="#" method="get">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#createUser">Add Blog Category</button>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>
                                    <a href="{{ route('blog-category', array_merge(request()->except('page'), [
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
                            @foreach ($data as $blogCategory)
                                <tr>
                                    <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                                    <td>
                                        {{ $blogCategory->name }}
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($blogCategory->created_at)->format('d M Y H:ia') }}
                                    </td>

                                    <td>
                                        <form id="deleteForm" method="POST" action="{{ route('delete-blog-category') }}"
                                            class="d-inline">
                                            @method('DELETE')
                                            @csrf
                                            <input type="text" name="blog_category_id" id="delete-blog-category-id" hidden>
                                            <button type="button" class="btn-link"
                                                style="border: none; background: none; padding: 0; margin: 0;"
                                                onclick="confirmDelete({{ $blogCategory->id }})">
                                                <i data-feather="trash-2" class="me-50"></i>
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mx-1 d-flex justify-content-end">
                        <nav aria-label="Page navigation">
                            <ul class="pagination mt-2">
                                <li class="page-item prev"><a class="page-link"
                                        style="pointer-events: {{ $data->currentPage() == 1 ? 'none' : '' }}"
                                        href="{{ $data->url($data->currentPage() - 1) }}"></a>
                                </li>
                                @for ($i = 1; $i <= $data->lastPage(); $i++)
                                    <li class="page-item {{ $i == $data->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="page-item next" disabled><a class="page-link"
                                        style="pointer-events: {{ $data->currentPage() == $data->lastPage() ? 'none' : '' }}"
                                        href="{{ $data->url($data->currentPage() + 1) }}"></a>
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
                        <h1 class="mb-1">Add new blog category</h1>
                    </div>

                    <div class="card-body">
                        <form class="form form-horizontal" action="{{ route('create-blog-category') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="first-name">Name<span style="color: red"> *
                                                </span></label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" id="name" class="form-control" name="name"
                                                placeholder="Name" required />
                                            @error('name')
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





    <!-- Hoverable rows end -->

@endsection

@section('vendor-script')
    <!-- vendor js files -->
    <script src="{{ asset(mix('vendors/js/pagination/jquery.bootpag.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pagination/jquery.twbsPagination.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/editors/quill/katex.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/editors/quill/highlight.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/editors/quill/quill.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/file-uploaders/dropzone.min.js')) }}"></script>

@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/pagination/components-pagination.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-quill-editor.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-file-uploader.js')) }}"></script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $("#success-alert").alert('close');
            }, 3000);
        });
    </script>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $("#error-alert").alert('close');
            }, 3000);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        function confirmDelete(blogCategoryId) {

            console.log(blogCategoryId);
            document.getElementById('delete-blog-category-id').value = blogCategoryId;
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
