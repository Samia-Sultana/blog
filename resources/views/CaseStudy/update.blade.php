@extends('layouts/contentLayoutMaster')

@section('title', 'Create Job')

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
<div class="row">
    <div class="col-12">

        <!-- profile -->
        <div class="card">

            <div class="card-body py-2 my-25">

                <!-- form -->
                <form class="validate-form pt-50" method="POST" action="{{ route('case.update', ['id' => $old->id]) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="title" name="title" placeholder="Enter title" value="{{ $old->title }}" data-msg="Please enter title" />
                        </div>
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="accountLastName">Slug <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="slug" name="slug" placeholder="slug" value="{{ $old->slug }}" data-msg="Please enter slug" />
                        </div>

                        <div class="col-md-6 mb-1">
                            <label class="form-label" for="category">Category</label>
                            <select class="select2 form-select" id="category" name="category">
                                <!-- @foreach ($category as $data)
                                <option value="{{ $data }}" {{ $old->category == $data ? 'selected' : '' }}>
                                    {{ $data }}
                                </option>
                                @endforeach -->

                                <option value="{{ $old->category }}" selected>{{ $old->category }}</option>
                            </select>
                        </div>

                        
                        <div class="col-12 col-sm-6 mb-1">
                            <label for="slug" class="form-label">Feature Image</label>

                            @if($old->featured_image)
                            <div class="mb-2" id="preview-container">
                                <img id="preview" src="{{ asset($old->featured_image) }}" alt="Featured Image" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                            @endif

                           

                            <input type="file" id="featured_image" name="featured_image" class="form-control" placeholder="Enter feature picture" onchange="previewImage(event)">
                            @error('featured_image')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                      

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="sort_desc">Sort Description <span class="text-danger">*</span></label>
                            <textarea required class="form-control" id="exampleFormControlTextarea1" name="sort_desc" rows="3">{{ $old->sort_desc }}</textarea>
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
    CKEDITOR.replace('content');
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
    document.getElementById('title').addEventListener('input', function(e) {
        var title = e.target.value.toLowerCase().trim();
        var slug = title.replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
        document.getElementById('slug').value = slug;
    });
</script>


@endsection