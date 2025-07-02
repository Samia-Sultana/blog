@extends('layouts/contentLayoutMaster')

@section('title', 'Create Content Case Study')

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
                <form class="validate-form pt-50" method="POST" action="{{ route('case.content.store', $id) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="header_title">Title <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="header_title" name="header_title" placeholder="Enter header_title " value="{{ old('header_title') }}" data-msg="Please enter title" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="client">client <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="client" name="client" placeholder="Enter client " value="{{ old('client') }}" data-msg="Please enter title" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="completed_on">completed on <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="completed_on" name="completed_on" placeholder="Enter completed on like December 2023 to February 2024" value="{{ old('completed_on') }}" data-msg="Please enter title" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label for="slug" class="form-label">Feature Image</label>

                            <div class="mb-2" id="preview-container" style="display: none;">
                                <img id="preview" alt="Featured Image" class="img-thumbnail" style="max-width: 200px;">
                            </div>

                            <input type="file" id="featured_image" name="featured_image" class="form-control" placeholder="Enter feature picture" onchange="previewImage(event)">
                            @error('featured_image')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <h4 class="my-2 bg-secondary text-white p-1">Project Overview Section</h4>

                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="po_title">Title <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="po_title" name="po_title" placeholder="Enter po_title ( length will be 50 - 52 characters )" value="{{ old('po_title') }}" data-msg="Please enter header_tittle" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="po_desc">Desc <span class="text-danger">*</span></label>
                            <textarea required id="po_desc" class="form-control" id="exampleFormControlTextarea1" placeholder="Enter sort description" name="po_desc" rows="3"></textarea>
                        </div>
                    </div>


                    <h4 class="my-2 bg-secondary text-white p-1">The Challenge Section</h4>

                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="challenge_title">Title <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="challenge_title" name="challenge_title" placeholder="Enter challenge_title ( length will be 50 - 52 characters )" value="{{ old('challenge_title') }}" data-msg="Please enter header_tittle" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="challenge_desc">Desc <span class="text-danger">*</span></label>
                            <textarea required id="challenge_desc" class="form-control" id="exampleFormControlTextarea1" placeholder="Enter sort description" name="challenge_desc" rows="3"></textarea>
                        </div>
                    </div>

                    <h4 class="my-2 bg-secondary text-white p-1">How Contetn Writing Helped Section</h4>

                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="helped_title">Title <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="helped_title" name="helped_title" placeholder="Enter helped_title ( length will be 50 - 52 characters )" value="{{ old('helped_title') }}" data-msg="Please enter header_tittle" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="helped_desc">Desc <span class="text-danger">*</span></label>
                            <textarea required id="helped_desc" class="form-control" id="exampleFormControlTextarea1" placeholder="Enter sort description" name="helped_desc" rows="3"></textarea>
                        </div>
                    </div>


                    <h4 class="my-2 bg-secondary text-white p-1">The Problem Section</h4>

                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="problem_title">problem_title <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="problem_title" name="problem_title" placeholder="Enter problem_title ( length will be 50 - 52 characters )" value="{{ old('problem_title') }}" data-msg="Please enter header_tittle" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label for="slug" class="form-label">Section 1 Image</label>

                            <div class="mb-2" id="preview_container_problem" style="display: none;">
                                <img id="preview_problem" alt="problem_image" class="img-thumbnail" style="max-width: 200px;">
                            </div>

                            <input type="file" id="problem_image" name="problem_image" class="form-control" placeholder="Enter feature picture" onchange="previewImageSec1(event)">
                            @error('problem_image')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="problem_desc">problem_desc <span class="text-danger">*</span></label>
                            <textarea required id="problem_desc" class="form-control" id="exampleFormControlTextarea1" placeholder="Enter sort description" name="problem_desc" rows="3"></textarea>
                        </div>
                    </div>

                    <h4 class="my-2 bg-secondary text-white p-1">The Challenge 2nd Section</h4>

                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="challenge_2_title">challenge_2_title <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="challenge_2_title" name="challenge_2_title" placeholder="Enter challenge_2_title" value="{{ old('challenge_2_title') }}" data-msg="Please enter header_tittle" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label for="slug" class="form-label">Section 2 Image</label>

                            <div class="mb-2" id="preview_container_challenge_2" style="display: none;">
                                <img id="preview_challenge_2" alt="challenge_2_image" class="img-thumbnail" style="max-width: 200px;">
                            </div>

                            <input type="file" id="challenge_2_image" name="challenge_2_image" class="form-control" placeholder="Enter feature picture" onchange="previewImageSec2(event)">
                            @error('challenge_2_image')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="challenge_2_desc">challenge_2_desc <span class="text-danger">*</span></label>
                            <textarea required id="challenge_2_desc" class="form-control" id="exampleFormControlTextarea1" placeholder="Enter sort description " name="challenge_2_desc" rows="2"></textarea>
                        </div>
                    </div>
                    <h4 class="my-2 bg-secondary text-white p-1">The Results Section</h4>
                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="result_title">result_title <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="result_title" name="result_title" placeholder="Enter result_title" value="{{ old('result_title') }}" data-msg="Please enter header_tittle" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label for="slug" class="form-label">Section 3 Image</label>

                            <div class="mb-2" id="preview_container_result" style="display: none;">
                                <img id="preview_result" alt="result_image" class="img-thumbnail" style="max-width: 200px;">
                            </div>

                            <input type="file" id="result_image" name="result_image" class="form-control" placeholder="Enter feature picture" onchange="previewImageSec3(event)">
                            @error('result_image')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="result_desc">result_desc <span class="text-danger">*</span></label>
                            <textarea required id="result_desc" class="form-control" id="exampleFormControlTextarea1" placeholder="Enter sort description " name="result_desc" rows="2"></textarea>
                        </div>
                    </div>

                    <h4 class="my-2 bg-secondary text-white p-1">Seo Section</h4>

                    <div class="row">
                        <div class="col-md-6 mb-1">
                            <label class="form-label" for="index_status">Index</label>
                            <select class="select2 form-select" id="index_status" name="index_status">
                                <option value="1" selected>Index</option>
                                <!-- <option value="2">No Index</option> -->
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="meta_title">Title(Meta)</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="Meta Title" value="{{ old('meta_title') }}" data-msg="Please enter meta title" />
                        </div>
                        <div class="col-12 col-sm-12 mb-1">
                            <label class="form-label" for="meta_description">Description (Meta)</label>
                            <textarea class="form-control" id="meta_description" name="meta_description" placeholder="Meta Description" value="{{ old('meta_description') }}" data-msg="Please enter meta description"></textarea>
                        </div>
                    </div>



                    <div class="col-12">
                        <button type="submit" class="btn btn-primary mt-1 me-1">Save changes</button>
                        <button type="reset" class="btn btn-outline-secondary mt-1">Discard</button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    CKEDITOR.replace('header_description');

    CKEDITOR.replace('po_desc');
    CKEDITOR.replace('challenge_desc');
    CKEDITOR.replace('helped_desc');

    CKEDITOR.replace('problem_desc');
    CKEDITOR.replace('challenge_2_desc');
    CKEDITOR.replace('result_desc');
    CKEDITOR.replace('sec_result_desc');
    CKEDITOR.replace('sec_ranking_desc');
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

    function previewImageSec1(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview_problem');
            output.src = reader.result;
            document.getElementById('preview_container_problem').style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function previewImageSec2(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview_challenge_2');
            output.src = reader.result;
            document.getElementById('preview_container_challenge_2').style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function previewImageSec3(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview_result');
            output.src = reader.result;
            document.getElementById('preview_container_result').style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

</script>






@endsection