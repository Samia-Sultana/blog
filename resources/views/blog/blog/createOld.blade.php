@extends('layouts/contentLayoutMaster')

@section('title', 'Create Blog')

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
    <div class="row" id="table-hover-row">
        <div class="col-12">
            <div class="card">

                <div class="card-body">
                    <div class="card">
                        <div id="sticky-wrapper" class="sticky-wrapper" style="height: 86.0625px;">
                            <div class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row"
                                style="width: 1390px;">
                                <h5 class="card-title mb-sm-0 me-2">Blog Form</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <form method="POST" action="{{ route('store-blog') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="col-lg-8 mx-auto">
                                        <h3>Blog Information</h3>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="col-md-12">
                                                    <label class="form-label" for="title">Title <span
                                                            style="color: red;">*</span></label>
                                                    <input type="text" id="title" name="title" class="form-control"
                                                        value="{{ old('title') }}" placeholder="Enter blog title">
                                                    @error('title')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label" for="title">Slug <span
                                                            style="color: red;">*</span></label>
                                                    <input type="text" id="slug" name="slug" class="form-control"
                                                        value="{{ old('slug') }}" placeholder="Enter blog slug">
                                                    @error('slug')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label" for="blog_category">Blog Category <span
                                                            style="color: red;">*</span></label>
                                                    <select id="blog_category" name="blog_category_id" class="form-select">
                                                        @foreach ($blogCategoryData as $blogCategory)
                                                            <option value="{{ $blogCategory->id }}">
                                                                {{ $blogCategory->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('blog_category_id')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label" for="title">Author</label>
                                                    <input type="text" id="author" name="author" class="form-control"
                                                        value="{{ old('author') }}" placeholder="Enter blog author name">
                                                    @error('author')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="col-md-12">
                                                    <label class="form-label" for="slug">Feature Picture<span
                                                            style="color: red;">*</span></label>
                                                    <input type="file" id="feature_picture" name="feature_picture"
                                                        class="form-control" placeholder="Enter feature picture">
                                                    @error('feature_picture')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12 mt-1">
                                                    <img id="preview-image-before-upload" hidden src=""
                                                        width="100%" alt="preview image" alt="preview image"
                                                        style="max-height: 250px;">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label" for="content">Content<span
                                                        style="color: red;">*</span></label>
                                                <textarea name="content" class="form-control" id="content" rows="2" placeholder="Enter blog content"></textarea>
                                                @error('content')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <hr>
                                        <!-- 2. Seo Features -->
                                        <h5 class="my-4">2. Seo Features</h5>

                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="index"
                                                        name="index_status" checked="">
                                                    <label class="form-check-label" for="index">Index</label>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label" for="canonical_url">Canonical URL</label>
                                                <input type="text" id="canonical_url" name="canonical_url"
                                                    class="form-control" placeholder="Enter canonical URL">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label" for="meta_title">Meta Title</label>
                                                <input type="text" id="meta_title" name="meta_title"
                                                    class="form-control" placeholder="Enter meta title">
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label" for="meta_description">Meta Description</label>
                                                <textarea name="meta_description" class="form-control" id="meta_description" rows="2"
                                                    placeholder="Enter meta description"></textarea>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label" for="meta_url">Meta URL</label>
                                                <input type="text" id="meta_url" name="meta_url"
                                                    class="form-control" placeholder="Enter meta URL">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label" for="meta_publish_date">Meta Publish
                                                    Date</label>
                                                <input type="date" id="meta_publish_date" name="meta_publish_date"
                                                    class="form-control" placeholder="Enter meta publish date">
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label" for="schema_markup">Schema Markup</label>
                                                <textarea name="schema_markup" class="form-control" id="schema_markup" rows="2"
                                                    placeholder="Enter schema markup"></textarea>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label" for="custom_code">Custom Code</label>
                                                <textarea name="custom_code" class="form-control" id="custom_code" rows="2" placeholder="Enter custom code"></textarea>
                                            </div>

                                        </div>
                                        <div style = "margin-top: 20px;">
                                            <div class="action-btns">
                                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                    Save
                                                </button>
                                            </div>
                                        </div>

                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
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
        CKEDITOR.replace('content');
    </script>

    <script>
        document.getElementById('title').addEventListener('input', function(e) {
            var title = e.target.value.toLowerCase().trim();
            var slug = title.replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
            document.getElementById('slug').value = slug;
        });

        // image preview
        const inputFile = document.getElementById('feature_picture');
        const previewContainer = document.getElementById('preview-image-before-upload');
        // set the image preview
        inputFile.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function() {
                    const result = reader.result;
                    previewContainer.src = result;
                    previewContainer.hidden = false;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
