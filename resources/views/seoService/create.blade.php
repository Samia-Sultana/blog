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

<style>
    .ck-editor__editable {
        min-height: 200px;
    }
</style>
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
                <form class="validate-form pt-50" method="POST" action="{{ route('seo.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <!-- Slug -->
                        <div class="col-12 col-sm-12 mb-1">
                            <label class="form-label" for="slug">Slug <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="slug" name="slug" placeholder="slug like (seo-service-bangladesh)" value="{{ old('slug') }}" data-msg="Please enter slug" />
                        </div>

                        <!-- 1st Section Title -->
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="title_1st_section">1st Section Title </label>
                            <input type="text"   class="form-control" id="title_1st_section" name="title_1st_section" placeholder="Enter title" value="{{ old('title_1st_section') }}" />
                        </div>

                        <!-- 1st Section Image -->
                        <div class="col-12 col-sm-5 mb-1">
                            <label class="form-label" for="image_1st_section">1st Section Image</label>
                            <input type="file" class="form-control" id="image_1st_section" name="image_1st_section" onchange="previewImage(event, 'preview1')">
                            <img id="preview1" alt="1st Section Image Preview" class="img-thumbnail mt-2" style="max-width: 200px; display: none;">

                        </div>

                        <div class="col-12 col-sm-1 d-flex flex-column">
                            <label class="form-label">
                                1st Section Active
                            </label>
                            <input class="form-check-input" type="checkbox" name="section_1st_is_active" checked id="flexCheckDefault">

                        </div>
                        <!-- 1st Section Description -->
                        <div class="col-12 col-sm-12 mb-1">
                            <label class="form-label" for="description_1st_section">1st Section Description</label>
                            <textarea name="description_1st_section" id="description_1st_section" class="form-control ckeditor" rows="2" placeholder="Enter description">{{ old('description_1st_section') }}</textarea>
                        </div>



                        <!-- 2nd Section Title -->
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="title_2nd_section">2nd Section Title </label>
                            <input type="text"  class="form-control" id="title_2nd_section" name="title_2nd_section" placeholder="Enter title" value="{{ old('title_2nd_section') }}" />
                        </div>

                        <!-- 2nd Section Video Link -->
                        <div class="col-12 col-sm-5 mb-1">
                            <label class="form-label" for="section_2nd_video_link">2nd Section Video Link</label>
                            <input type="text"  class="form-control" id="section_2nd_video_link" name="section_2nd_video_link" placeholder="Enter video link" value="{{ old('section_2nd_video_link') }}" />
                        </div>

                        <div class="col-12 col-sm-1 d-flex flex-column">
                            <label class="form-label">
                                2st Section Active
                            </label>
                            <input class="form-check-input" type="checkbox" name="section_2nd_is_active" checked id="flexCheckDefault">

                        </div>

                        <!-- 2nd Section Description -->
                        <div class="col-12 col-sm-12 mb-1">
                            <label class="form-label" for="description_2nd_section">2nd Section Description</label>
                            <textarea name="description_2nd_section" id="description_2nd_section" class="form-control ckeditor" rows="2" placeholder="Enter description">{{ old('description_2nd_section') }}</textarea>
                        </div>



                        <!-- 3rd Section Title -->
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="title_3rd_section">3rd Section Title </label>
                            <input type="text"  class="form-control" id="title_3rd_section" name="title_3rd_section" placeholder="Enter title" value="{{ old('title_3rd_section') }}" />
                        </div>

                        <!-- 3rd Section Image -->
                        <div class="col-12 col-sm-5 mb-1">
                            <label class="form-label" for="section_3rd_image">3rd Section Image</label>
                            <input type="file" class="form-control" id="section_3rd_image" name="section_3rd_image" onchange="previewImage(event, 'preview3')">
                            <img id="preview3" alt="3rd Section Image Preview" class="img-thumbnail mt-2" style="max-width: 200px; display: none;">

                        </div>
                        <div class="col-12 col-sm-1 d-flex flex-column">
                            <label class="form-label">
                                3rd Section Active
                            </label>
                            <input class="form-check-input" type="checkbox" name="section_3rd_is_active" checked id="flexCheckDefault">

                        </div>

                        <!-- 3rd Section Description -->
                        <div class="col-12 col-sm-12 mb-1">
                            <label class="form-label" for="description_3rd_section">3rd Section Description</label>
                            <textarea name="description_3rd_section" id="description_3rd_section" class="form-control ckeditor" rows="2" placeholder="Enter description">{{ old('description_3rd_section') }}</textarea>
                        </div>



                        <!-- Case Study 1st Image -->
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="case_study_1st_image">Case Study 1st Image</label>
                            <input type="file" class="form-control" id="case_study_1st_image" name="case_study_1st_image" onchange="previewImage(event, 'previewCase1')">
                            <img id="previewCase1" alt="Case Study 1st Image Preview" class="img-thumbnail mt-2" style="max-width: 200px; display: none;">

                        </div>

                        <!-- Case Study 2nd Image -->
                        <div class="col-12 col-sm-5 mb-1">
                            <label class="form-label" for="case_study_2nd_image">Case Study 2nd Image</label>
                            <input type="file" class="form-control" id="case_study_2nd_image" name="case_study_2nd_image" onchange="previewImage(event, 'previewCase2')">
                            <img id="previewCase2" alt="Case Study 2nd Image Preview" class="img-thumbnail mt-2" style="max-width: 200px; display: none;">

                        </div>

                        <div class="col-12 col-sm-1 d-flex flex-column">
                            <label class="form-label">
                                Case Study Active
                            </label>
                            <input class="form-check-input" type="checkbox" name="case_stydy_section_is_active" checked id="flexCheckDefault">

                        </div>
                        <!-- Meta Title -->
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="meta_title">Meta Title  <span class="text-danger">*</span> </label>
                            <input type="text" required class="form-control" id="meta_title" name="meta_title" placeholder="Enter meta title" value="{{ old('meta_title') }}" />
                        </div>

                        <div class="col-md-6 mb-1">
                            <label class="form-label" for="index_status">Index</label>
                            <select class="select2 form-select" id="index_status" name="index_status">
                                <option value="1">Index</option>
                                <option value="2">No Index</option>
                            </select>
                        </div>

                        <!-- Meta Description -->
                        <div class="col-12 mb-1">
                            <label class="form-label" for="meta_description">Meta Description <span class="text-danger">*</span></label>
                            <textarea name="meta_description" required id="meta_description" class="form-control" rows="2" placeholder="Enter meta description">{{ old('meta_description') }}</textarea>
                        </div>

                        <!-- Submit and Reset Buttons -->
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

<!-- <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script> -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content');
</script>

<script>
    function previewImage(event, previewId) {
        const [file] = event.target.files;
        if (file) {
            const preview = document.getElementById(previewId);
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }
    }
</script>

<script>
    document.getElementById('title').addEventListener('input', function(e) {
        var title = e.target.value.toLowerCase().trim();
        var slug = title.replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
        document.getElementById('slug').value = slug;
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Apply CKEditor to each textarea with the 'ckeditor' class
        document.querySelectorAll('.ckeditor').forEach((textarea) => {
            ClassicEditor
                .create(textarea, {

                    height: '300px'
                })
                .catch(error => {
                    console.error(error);
                });
        });
    });
</script>


@endsection
