@extends('layouts/contentLayoutMaster')

@section('title', 'Edit Blog')

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
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-pills mb-2">
                <!-- Account -->
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('create-blog') }}">
                        <i data-feather="user" class="font-medium-3 me-50"></i>
                        <span class="fw-bold">Edit General Info</span>
                    </a>
                </li>
                <!-- security -->
                {{-- <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i data-feather="lock" class="font-medium-3 me-50"></i>
                        <span class="fw-bold">Content</span>
                    </a>
                </li> --}}
                <!-- billing and plans -->
                {{-- <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i data-feather="bookmark" class="font-medium-3 me-50"></i>
                        <span class="fw-bold">SEO</span>
                    </a>
                </li> --}}

            </ul>

            <!-- profile -->
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">Edit General Info</h4>
                </div>
                <div class="card-body py-2 my-25">

                    @php
                        $segments = request()->segments();
                        $id = $segments[count($segments) - 2];
                    @endphp

                    <!-- form -->
                    <form class="validate-form pt-50" method="POST" action="{{ route('update-blog', ['id' => $id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')



                        <input type="hidden" name="blog_id" value="{{ $id }}">

                        <div class="row">
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="accountTitle">Title</label>
                                <input type="text" class="form-control" id="accountTitle" name="title"
                                    placeholder="Enter title" value="{{ $data->title }}" data-msg="Please enter title" />
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="accountLastName">Slug</label>
                                <input type="text" class="form-control" id="slug" name="slug" placeholder="slug"
                                    value="{{ $data->slug }}" data-msg="Please enter slug" />
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="blog_category">Blog Category</label>
                                <select class="select2 form-select" id="blog_category" name="blog_category[]" multiple>
                                    <optgroup>
                                        @foreach ($blogCategoryData as $blogCategory)
                                            <option value="{{ $blogCategory->id }}" {{ in_array($blogCategory->id, $data->blogCategories->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                {{ $blogCategory->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>

                            {{-- <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="accountLastName">Author</label>
                                <input type="text" class="form-control" id="author" name="author"
                                    placeholder="author" value="{{ old('author') }}" data-msg="Please enter author" />
                            </div> --}}

                            <div class="col-md-6 mb-1">
                                <label class="form-label" for="author_id">Author</label>
                                <select class="select2 form-select" id="author_id" name="author_id">
                                    @foreach ($authorData as $author)
                                        <option value="{{ $author->id }}" {{ $author->id == $data->authors->id ? 'selected' : '' }}>
                                            {{ $author->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="published_at">Published At</label>
                                <input type="date" class="form-control" id="published_at" name="published_at"
                                    placeholder="date" value="{{ $data->published_at }}"
                                    data-msg="Please enter published date" />
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label for="slug" class="form-label">Feature Picture</label>

                                @if($data->featured_image)
                                    <div class="mb-2">
                                        <img id="preview" src="{{ asset($data->featured_image) }}" alt="Featured Image" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                @endif

                                <input type="file" id="feature_picture" name="feature_picture" class="form-control" placeholder="Enter feature image" onchange="previewImage(event)">

                                @error('feature_picture')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mt-1">
                                <img id="preview-image-before-upload" hidden src="" width="100%"
                                    alt="preview image" alt="preview image" style="max-height: 250px;">
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary mt-1 me-1">Save changes</button>
                                <button type="reset" class="btn btn-outline-secondary mt-1">Discard</button>
                            </div>
                        </div>
                    </form>
                    <!--/ form -->
                </div>
            </div>


        </div>
    </div>
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

    <script src="//cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('description');
    </script>
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
        </script>


@endsection
