@extends('layouts/contentLayoutMaster')

@section('title', 'Image')

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
                    <form method="GET" action="{{ route('og-images') }}">
                        <!-- @csrf -->
                        <div class="input-group">
                            <input type="text" name="query" value="{{ $searchQuery }}" class="form-control me-1" placeholder="Search by Name and Slug">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-outline-primary">Search</button>
                            </div>
                        </div>
                    </form>
                    <form action="#" method="get">
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createUser">Add Images</button>
                    </form>
                </div>

            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Image</th>
                            <th>
                                <a href="{{ route('og-images', array_merge(request()->except('page'), [
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $ogImage)
                        <tr>
                            <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                            <td>
                                {{ $ogImage->name }}
                            </td>

                            <td>
                                {{ $ogImage->slug }}
                            </td>

                            <td>
                                <img id="ogImage" src="{{ asset($ogImage->image) }}" alt="Image" style="max-width: 100px;">
                            </td>
                            <td>{{ \Carbon\Carbon::parse($ogImage->created_at)->format('d M Y H:ia') }}</td>
                            <td>
                                <form id="deleteForm" method="POST" action="{{ route('delete-og-images') }}" class="d-inline">
                                    @method('DELETE')
                                    @csrf
                                    <input type="text" name="og_image_id" id="delete-blog-category-id" hidden>
                                    <button type="button" class="btn-link" style="border: none; background: none; padding: 0; margin: 0;" onclick="confirmDelete({{ $ogImage->id }})">
                                        <i data-feather="trash-2" class="me-50"></i>
                                    </button>
                                </form>

                                <button type="button" class="btn-link copy-btn" style="border: none; background: none; padding: 0; margin: 0;" onclick="copyImageSrc('{{ asset($ogImage->image) }}')">
                                    <i data-feather="copy" class="me-50"></i>
                                </button>

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
                    <h1 class="mb-1">Add Image</h1>
                </div>

                <div class="card-body">
                    <form class="form form-horizontal" action="{{ route('store-og-image') }}" method="POST" enctype="multipart/form-data">
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
                                <div class="mb-1 row">
                                    <div class="col-sm-3">
                                        <label class="col-form-label" for="first-name">Slug<span style="color: red">
                                                *
                                            </span></label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" id="slug" class="form-control" name="slug" placeholder="Slug" required />
                                        @error('slug')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-1 row">
                                    <div class="col-sm-3">
                                        <label class="col-form-label" for="first-name">Image<span style="color: red">
                                                *
                                            </span></label>
                                    </div>

                                    <div class="col-sm-5">
                                        <input type="file" id="image" class="form-control" name="image" onchange="previewImage(event)" placeholder="Image" required />
                                        @error('image')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-4" id="preview-container" style="display: none;">
                                        <img id="preview" alt="Featured Image" class="img-thumbnail" style="max-width: 200px;">
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
    function confirmDelete(ogImageId) {

        console.log(ogImageId);
        document.getElementById('delete-blog-category-id').value = ogImageId;
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
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview');
            output.src = reader.result;
            document.getElementById('preview-container').style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>


<script>
    function copyImageSrc(url) {

        var imageElement = document.getElementById('ogImage');

        if (imageElement) {
            var imageSrc = url;

            var tempInput = document.createElement('input');
            tempInput.setAttribute('value', imageSrc);
            document.body.appendChild(tempInput);
            tempInput.select();
            tempInput.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand('copy');
            document.body.removeChild(tempInput);

            alert('Image link copied to clipboard.');
        } else {
            console.error('Image element with ID "ogImage" not found.');
        }
    }
</script>





@endsection
