@extends('layouts/contentLayoutMaster')

@section('title', 'Create Software Case Study')

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
                <form class="validate-form pt-50" method="POST" action="{{ route('case.software.store', $id) }}" enctype="multipart/form-data">
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

                            <div class="mb-2" id="preview_container" style="display: none;">
                                <img id="preview" alt="Featured Image" class="img-thumbnail" style="max-width: 200px;">
                            </div>

                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <input type="file" id="featured_image" name="featured_image" class="form-control" placeholder="Enter feature picture" onchange="previewImage(event, 'preview', 'preview_container')">
                                    @error('featured_image')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-sm-6">
                                    <input required type="text" class="form-control" id="featured_image_alt" name="featured_image_alt" placeholder="Enter image alt" />

                                </div>
                            </div>




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

                    <h4 class="my-2 bg-secondary text-white p-1">The Problems Section</h4>

                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="problem_title">Title <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="problem_title" name="problem_title" placeholder="Enter problem_title ( length will be 50 - 52 characters )" value="{{ old('problem_title') }}" data-msg="Please enter header_tittle" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="problem_desc">Desc <span class="text-danger">*</span></label>
                            <textarea required id="problem_desc" class="form-control" id="exampleFormControlTextarea1" placeholder="Enter sort description" name="problem_desc" rows="3"></textarea>
                        </div>
                    </div>


                    <h4 class="my-2 bg-secondary text-white p-1">Middle Images Section</h4>

                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="middle_1st_image">First Image<span class="text-danger">*</span></label>
                            <div class="mb-2" id="middle_1st_image_preview_container" style="display: none;">
                                <img id="middle_1st_image_preview" alt="Featured Image" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                            <input type="file" id="middle_1st_image" name="middle_1st_image" class="form-control" placeholder="Enter feature picture" onchange="previewImage(event, 'middle_1st_image_preview', 'middle_1st_image_preview_container')">
                            <input required type="text" class="form-control mt-1" id="middle_1st_image_alt" name="middle_1st_image_alt" placeholder="Enter image alt" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="middle_2nd_image">Second Image<span class="text-danger">*</span></label>
                            <div class="mb-2" id="middle_2nd_image_preview_container" style="display: none;">
                                <img id="middle_2nd_image_preview" alt="Featured Image" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                            <input type="file" id="middle_2nd_image" name="middle_2nd_image" class="form-control" placeholder="Enter feature picture" onchange="previewImage(event, 'middle_2nd_image_preview', 'middle_2nd_image_preview_container')">
                            <input required type="text" class="form-control mt-1" id="middle_2nd_image_alt" name="middle_2nd_image_alt" placeholder="Enter image alt" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="middle_3rd_image">Third Image<span class="text-danger">*</span></label>
                            <div class="mb-2" id="middle_3rd_image_preview_container" style="display: none;">
                                <img id="middle_3rd_image_preview" alt="Featured Image" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                            <input type="file" id="middle_3rd_image" name="middle_3rd_image" class="form-control" placeholder="Enter feature picture" onchange="previewImage(event, 'middle_3rd_image_preview', 'middle_3rd_image_preview_container')">
                            <input required type="text" class="form-control mt-1" id="middle_3rd_image_alt" name="middle_3rd_image_alt" placeholder="Enter image alt" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="middle_4th_image">Fourth Image<span class="text-danger">*</span></label>
                            <div class="mb-2" id="middle_4th_image_preview_container" style="display: none;">
                                <img id="middle_4th_image_preview" alt="Featured Image" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                            <input type="file" id="middle_4th_image" name="middle_4th_image" class="form-control" placeholder="Enter feature picture" onchange="previewImage(event, 'middle_4th_image_preview', 'middle_4th_image_preview_container')">
                            <input required type="text" class="form-control mt-1" id="middle_4th_image_alt" name="middle_4th_image_alt" placeholder="Enter image alt" />
                        </div>
                    </div>


                    <h4 class="my-2 bg-secondary text-white p-1">Workflow scenario Section</h4>

                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="workflow_title">Title <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="workflow_title" name="workflow_title" placeholder="Enter workflow_title" value="{{ old('workflow_title') }}" data-msg="Please enter header_tittle" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="workflow_desc">Desc <span class="text-danger">*</span></label>
                            <textarea required id="workflow_desc" class="form-control" id="exampleFormControlTextarea1" placeholder="Enter sort description" name="workflow_desc" rows="3"></textarea>
                        </div>
                    </div>

                    <h4 class="my-2 bg-secondary text-white p-1">Solutions Section</h4>

                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="solution_title">Title <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="solution_title" name="solution_title" placeholder="Enter solution_title" value="{{ old('solution_title') }}" data-msg="Please enter header_tittle" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="solution_desc">Desc <span class="text-danger">*</span></label>
                            <textarea required id="solution_desc" class="form-control" id="exampleFormControlTextarea1" placeholder="Enter sort description" name="solution_desc" rows="3"></textarea>
                        </div>
                    </div>


                    <h4 class="my-2 bg-secondary text-white p-1">Bottom Images Section</h4>

                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="bottom_1st_image">First Image<span class="text-danger">*</span></label>
                            <div class="mb-2" id="bottom_1st_image_preview_container" style="display: none;">
                                <img id="bottom_1st_image_preview" alt="Featured Image" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                            <input type="file" id="bottom_1st_image" name="bottom_1st_image" class="form-control" placeholder="Enter feature picture" onchange="previewImage(event, 'bottom_1st_image_preview', 'bottom_1st_image_preview_container')">
                            <input required type="text" class="form-control mt-1" id="bottom_1st_image_alt" name="bottom_1st_image_alt" placeholder="Enter image alt" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="bottom_2nd_image">Second Image<span class="text-danger">*</span></label>
                            <div class="mb-2" id="bottom_2nd_image_preview_container" style="display: none;">
                                <img id="bottom_2nd_image_preview" alt="Featured Image" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                            <input type="file" id="bottom_2nd_image" name="bottom_2nd_image" class="form-control" placeholder="Enter feature picture" onchange="previewImage(event, 'bottom_2nd_image_preview', 'bottom_2nd_image_preview_container')">
                            <input required type="text" class="form-control mt-1" id="bottom_2nd_image_alt" name="bottom_2nd_image_alt" placeholder="Enter image alt" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="bottom_3rd_image">Third Image<span class="text-danger">*</span></label>
                            <div class="mb-2" id="bottom_3rd_image_preview_container" style="display: none;">
                                <img id="bottom_3rd_image_preview" alt="Featured Image" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                            <input type="file" id="bottom_3rd_image" name="bottom_3rd_image" class="form-control" placeholder="Enter feature picture" onchange="previewImage(event, 'bottom_3rd_image_preview', 'bottom_3rd_image_preview_container')">
                            <input required type="text" class="form-control mt-1" id="bottom_3rd_image_alt" name="bottom_3rd_image_alt" placeholder="Enter image alt" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="bottom_4th_image">Fourth Image<span class="text-danger">*</span></label>
                            <div class="mb-2" id="bottom_4th_image_preview_container" style="display: none;">
                                <img id="bottom_4th_image_preview" alt="Featured Image" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                            <input type="file" id="bottom_4th_image" name="bottom_4th_image" class="form-control" placeholder="Enter feature picture" onchange="previewImage(event, 'bottom_4th_image_preview', 'bottom_4th_image_preview_container')">
                            <input required type="text" class="form-control mt-1" id="bottom_4th_image_alt" name="bottom_4th_image_alt" placeholder="Enter image alt" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="bottom_5th_image">Fifth Image<span class="text-danger">*</span></label>
                            <div class="mb-2" id="bottom_5th_image_preview_container" style="display: none;">
                                <img id="bottom_5th_image_preview" alt="Featured Image" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                            <input type="file" id="bottom_5th_image" name="bottom_5th_image" class="form-control" placeholder="Enter feature picture" onchange="previewImage(event, 'bottom_5th_image_preview', 'bottom_5th_image_preview_container')">
                            <input required type="text" class="form-control mt-1" id="bottom_5th_image_alt" name="bottom_5th_image_alt" placeholder="Enter image alt" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="bottom_6th_image">Sixth Image<span class="text-danger">*</span></label>
                            <div class="mb-2" id="bottom_6th_image_preview_container" style="display: none;">
                                <img id="bottom_6th_image_preview" alt="Featured Image" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                            <input type="file" id="bottom_6th_image" name="bottom_6th_image" class="form-control" placeholder="Enter feature picture" onchange="previewImage(event, 'bottom_6th_image_preview', 'bottom_6th_image_preview_container')">
                            <input required type="text" class="form-control mt-1" id="bottom_6th_image_alt" name="bottom_6th_image_alt" placeholder="Enter image alt" />
                        </div>

                    </div>

                    <h4 class="my-2 bg-secondary text-white p-1">Conclusion Section</h4>

                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="conclusion_title">Title <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="conclusion_title" name="conclusion_title" placeholder="Enter conclusion_title" value="{{ old('conclusion_title') }}" data-msg="Please enter header_tittle" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="conclusion_desc">Desc <span class="text-danger">*</span></label>
                            <textarea required id="conclusion_desc" class="form-control" id="exampleFormControlTextarea1" placeholder="Enter sort description" name="conclusion_desc" rows="3"></textarea>
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
    CKEDITOR.replace('problem_desc');
    CKEDITOR.replace('workflow_desc');
    CKEDITOR.replace('solution_desc');
    CKEDITOR.replace('conclusion_desc');
</script>

<script>
    function previewImage(event, imageDivId, containerId) {
        console.log("image", imageDivId, containerId);
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById(imageDivId);
            output.src = reader.result;
            document.getElementById(containerId).style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>






@endsection