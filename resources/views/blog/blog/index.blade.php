@extends('layouts/contentLayoutMaster')

@section('title', 'Blog')

@section('vendor-style')
<!-- vendor css files -->
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/katex.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/monokai-sublime.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.snow.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.bubble.css')) }}">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Inconsolata&family=Roboto+Slab&family=Slabo+27px&family=Sofia&family=Ubuntu+Mono&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/file-uploaders/dropzone.min.css')) }}">
@endsection

@section('page-style')
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-quill-editor.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-file-uploader.css')) }}">

<link rel="stylesheet" href="{{ asset('css/style.css') }}">


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
                <div class="d-flex justify-content-between">
                    <form method="GET" action="{{ route('blog') }}">
                        <!-- @csrf -->
                        <div class="input-group">
                            <input type="text" name="query" value="{{ $searchQuery }}" class="form-control me-1" placeholder="Search by ID, Title and Slug">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-outline-primary">Search</button>
                                @if (isset($searchQuery))
                                    <a href="{{ route('blog') }}" class="btn btn-outline-primary">Clear</a>
                                @endif
                            </div>
                        </div>
                    </form>
                    <a href="{{ route('create-blog') }}" class="btn btn-outline-primary">Add Blog</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>
                                <a href="{{ route('blog', array_merge(request()->except('page'), [
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
                            <th>Updated at</th>
                            <th>Published</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $blog)
                        <tr>

                            <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>

                            <td>
                                {{ $blog->title }}
                            </td>
                            <td>
                                @foreach($blog->blogCategories as $category)
                                <b> {{ $category->name }}, </b>
                                @endforeach
                            </td>

                            <td>
                                <b> {{ $blog->authors->name }}</b>
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($blog->created_at)->format('d M Y H:ia') }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($blog->updated_at)->format('d M Y H:ia') }}
                            </td>

                            <td class="text-center">

                                <input type="checkbox" class="item-checkbox" {{ $blog->ispublished ? 'checked' : ''}} data-id="{{ $blog->id }}">

                            </td>

                            <td>
                                <div class="d-flex">


                                    <a class="" href="{{ route('view-blog', ['id' => $blog->id]) }}">
                                    <i data-feather="eye" class="me-50"></i>
                                    </a>
                                    <a class="" href="{{ route('edit-blog', ['id' => $blog->id]) }}">
                                        <i data-feather="edit-2" class="me-50"></i>
                                    </a>
                                    <form id="deleteForm" method="POST" action="{{ route('delete-blog') }}" class="d-inline">
                                        @method('DELETE')
                                        @csrf
                                        <input type="text" name="blog_id" id="delete-blog-id" hidden>
                                        <button type="button" class="btn-link" style="border: none; background: none; padding: 0; margin: 0;" onclick="confirmDelete({{ $blog->id }})">
                                            <i data-feather="trash-2" class="me-50"></i>
                                        </button>
                                    </form>

                                    <a class="" onclick="copyUrl({{$blog}})">
                                        <i data-feather="copy" class="me-50"></i>
                                    </a>
                                </div>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mx-1 d-flex justify-content-end">
                    <nav aria-label="Page navigation">
                        <ul class="pagination mt-2">
                            <li class="page-item prev"><a class="page-link" style="pointer-events: {{ $data->currentPage() == 1 ? 'none' : '' }}" href="{{ $data->url($data->currentPage() - 1) }}"></a>
                            </li>
                            @for ($i = 1; $i <= $data->lastPage(); $i++)
                                <li class="page-item {{ $i == $data->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $data->appends(['query' => $searchQuery])->url($i) }}">{{ $i }}</a>
                                </li>
                                @endfor
                                <li class="page-item next" disabled><a class="page-link" style="pointer-events: {{ $data->currentPage() == $data->lastPage() ? 'none' : '' }}" href="{{ $data->url($data->currentPage() + 1) }}"></a>
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
                                        <input type="text" id="name" class="form-control" name="name" placeholder="Name" required />
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
    function confirmDelete(blogId) {
        document.getElementById('delete-blog-id').value = blogId;
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
    const slugify = (str) => {
        str = str.replace(/^\s+|\s+$/g, '');
        str = str.toLowerCase();
        str = str.replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        return str;
    }

    function copyUrl(blog) {
        const cat = slugify(blog?.blog_categories[0]?.name)
        const slug = blog?.slug
        const baseUrl = "<?php echo env('FRONTEND_URL'); ?>";
        const currentUrl = baseUrl + '/blog/' + cat + '/' + slug

        var tempInput = document.createElement("input");
        tempInput.value = currentUrl;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);

    }
</script>

<script>
    $(document).ready(function() {
        $('.item-checkbox').change(function() {
            var checkbox = $(this);
            var itemId = checkbox.data('id');
            var isChecked = checkbox.is(':checked');

            Swal.fire({
                title: 'Are you sure?',
                text: isChecked ?"Do you want to publish the blog?":"Do you want to make it draft?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: isChecked ?'Yes, publish it!':'Yes, make it draft!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("publish-blog") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            blog_id: itemId,
                            ispublished: isChecked
                        },
                        success: function(response) {

                            Swal.fire({
                                title: 'Success',
                                text: 'Blog successfully published.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Error',
                                text: 'An error occurred while updating the status.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                } else {
                    // Revert the checkbox state if the user cancels the confirmation
                    checkbox.prop('checked', !isChecked);
                }
            });
        });
    });
</script>




@endsection
