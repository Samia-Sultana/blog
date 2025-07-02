@extends('layouts/contentLayoutMaster')

@section('title', 'Update SEO Case Study')

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
                <form class="validate-form pt-50" method="POST" action="{{ route('case.seo.update', $old->id) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="header_title">Title <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="header_title" name="header_title" placeholder="Enter header_title " value="{{ $old->header_title }}" data-msg="Please enter title" />
                        </div>


                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="seo_conversion">Traffic Increase <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="seo_conversion" name="seo_conversion" placeholder="Enter seo_conversion" value="{{ $old->seo_conversion }}" data-msg="Please enter title" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="seo_incress">Current Impressions <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="seo_incress" name="seo_incress" placeholder="Enter seo_incress" value="{{ $old->seo_incress }}" data-msg="Please enter title" />
                        </div>
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="revenue">Increased Revenue <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="revenue" name="revenue" placeholder="Enter revenue" value="{{ $old->revenue }}" data-msg="Please enter title" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label for="slug" class="form-label">Feature Image</label>

                            @if($old->featured_image)
                            <div class="mb-2">
                                <img id="preview" src="{{ asset($old->featured_image) }}" alt="Featured Image" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                            @endif

                            <div class="mb-2" id="preview-container" style="display: none;">
                                <img id="preview" alt="Featured Image" class="img-thumbnail" style="max-width: 200px;">
                            </div>

                            <input type="file" id="featured_image" name="featured_image" class="form-control" placeholder="Enter feature picture" onchange="previewImage(event)">
                            @error('featured_image')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="header_description">Description <span class="text-danger">*</span></label>
                            <textarea required class="form-control" id="exampleFormControlTextarea1" placeholder="Enter sort description " name="header_description" id="header_description" rows="3">
                            {{ $old->header_description }}
                            </textarea>
                        </div>


                    </div>


                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="sec_1_title">sec_1_title <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="sec_1_title" name="sec_1_title" placeholder="Enter sec_1_title ( length will be 50 - 52 characters )" value="{{ $old->sec_1_title }}" data-msg="Please enter header_tittle" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label for="slug" class="form-label">Section 1 Image</label>

                            @if($old->sec_1_image)
                            <div class="mb-2">
                                <img id="preview_sec_1" src="{{ asset($old->sec_1_image) }}" alt="sec_1_image" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                            @endif
                            <div class="mb-2" id="preview_container_sec_1" style="display: none;">
                                <img id="preview_sec_1" alt="sec_1_image" class="img-thumbnail" style="max-width: 200px;">
                            </div>

                            <input type="file" id="sec_1_image" name="sec_1_image" class="form-control" placeholder="Enter feature picture" onchange="previewImageSec1(event)">
                            @error('sec_1_image')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="sec_1_desc">sec_1_desc <span class="text-danger">*</span></label>
                            <textarea required id="sec_1_desc" class="form-control" id="exampleFormControlTextarea1" placeholder="Enter sort description" name="sec_1_desc" rows="3">
                            {{ $old->sec_1_desc }}
                            </textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="sec_2_title">sec_2_title <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="sec_2_title" name="sec_2_title" placeholder="Enter sec_2_title" value="{{ $old->sec_2_title }}" data-msg="Please enter header_tittle" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label for="slug" class="form-label">Section 2 Image</label>

                            @if($old->sec_2_image)
                            <div class="mb-2">
                                <img id="preview_sec_2" src="{{ asset($old->sec_2_image) }}" alt="sec_2_image" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                            @endif

                            <div class="mb-2" id="preview_container_sec_2" style="display: none;">
                                <img id="preview_sec_2" alt="sec_2_image" class="img-thumbnail" style="max-width: 200px;">
                            </div>

                            <input type="file" id="sec_2_image" name="sec_2_image" class="form-control" placeholder="Enter feature picture" onchange="previewImageSec2(event)">
                            @error('sec_2_image')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="sec_2_desc">sec_2_desc <span class="text-danger">*</span></label>
                            <textarea required id="sec_2_desc" class="form-control" id="exampleFormControlTextarea1" placeholder="Enter sort description " name="sec_2_desc" rows="2">
                            {{ $old->sec_2_desc }}
                            </textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="sec_3_title">sec_3_title <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="sec_3_title" name="sec_3_title" placeholder="Enter sec_3_title" value="{{ $old->sec_3_title }}" data-msg="Please enter header_tittle" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label for="slug" class="form-label">Section 3 Image</label>

                            @if($old->sec_3_image)
                            <div class="mb-2">
                                <img id="preview_sec_3" src="{{ asset($old->sec_3_image) }}" alt="sec_3_image" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                            @endif
                            <div class="mb-2" id="preview_container_sec_3" style="display: none;">
                                <img id="preview_sec_3" alt="sec_3_image" class="img-thumbnail" style="max-width: 200px;">
                            </div>

                            <input type="file" id="sec_3_image" name="sec_3_image" class="form-control" placeholder="Enter feature picture" onchange="previewImageSec3(event)">
                            @error('sec_3_image')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="sec_3_desc">sec_3_desc <span class="text-danger">*</span></label>
                            <textarea required id="sec_3_desc" class="form-control" id="exampleFormControlTextarea1" placeholder="Enter sort description " name="sec_3_desc" rows="2">
                            {{ $old->sec_3_desc }}
                            </textarea>
                        </div>
                    </div>

                    <h4 class="my-2 bg-secondary text-white p-1">Our approach to the solution</h4>

                    <div id="content-sections">
                        @foreach ($old->solution as $item)

                        <input type="text" name="solution_id[]" value="{{ $item->id }}" hidden>

                        <div class="content-section">
                            <div class="row">
                                <div class="col-5 col-sm-5 mb-1">
                                    <label class="form-label" for="accountName">Name</label>
                                    <input type="text" class="form-control" name="solution_name[]" value="{{ $item->name }}" placeholder="Enter content Name">
                                </div>

                                <div class="col-5 col-sm-5 mb-1">
                                    <label class="form-label" for="accountTitle">Title</label>
                                    <input type="text" class="form-control" name="solution_title[]" value="{{ $item->title }}" placeholder="Enter content title">
                                </div>

                                <div class="col-2 col-sm-2 mb-1">
                                    <label class="form-label" for="sequence">Sequence</label>
                                    <input required type="number" class="form-control" name="solution_sequence[]" value="{{ $item->sequence }}" placeholder="Enter No.">
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 mb-1">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea required name="solution_description[]" class="form-control ckeditor" rows="2" placeholder="Enter blog description">
                                    {{ $item->description }}
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 mb-1">

                                @if($item->image)
                                <div class="mb-2">
                                    <img id="" src="{{ asset($item->image) }}" alt="solution_image" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                                @endif

                                <label for="slug" class="form-label"> Image</label>
                                <input type="file" id="solution_image" name="solution_image[]" class="form-control" placeholder="Enter feature picture" onchange="previewImage(event)">
                            </div>



                            <button type="button" class="btn btn-danger mb-1 me-1 remove1" id="remove">Remove</button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-primary mb-1 me-1" id="add">Add</button>


                    <h4 class="my-2 bg-secondary text-white p-1">Slider Images (Add Minmum 2 Images)</h4>

                    <div id="image-sections">



                        @foreach ($old->slider as $item)
                        <div class="image-section">

                        <input type="text" name="slider_image_id[]" value="{{ $item->id }}" hidden>
                        <input type="text" name="slider_image_all[]" value="slider_image" hidden>


                            @if($item->image)
                            <div class="mb-2">
                                <img id="" src="{{ asset($item->image) }}" alt="sec_3_image" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                            @endif
                            <div class="mb-2" id="" style="display: none;">
                                <img id="" alt="sec_3_image" class="img-thumbnail" style="max-width: 200px;">
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label for="slug" class="form-label"> Image</label>
                                <input  type="file" id="sec_3_image" name="slider_image[]" class="form-control" placeholder="Enter feature picture">
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label for="slug" class="form-label"> Image Alt</label>
                                <input required type="text" id="sec_3_image" value="{{ $item->image_alt }}" name="image_alt[]" class="form-control" placeholder="Enter feature picture">
                            </div>

                            <button type="button" class="btn btn-danger mb-1 me-1 removeImage">Remove</button>
                        </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-primary mb-1 me-1" id="addImage">Add</button>


                    <h4 class="my-2 bg-secondary text-white p-1">Result Section</h4>

                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="sec_result_title">sec_result_title <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="sec_result_title" name="sec_result_title" placeholder="Enter sec_result_title" value="{{ $old->sec_result_title }}" data-msg="Please enter header_tittle" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="sec_result_desc">sec_result_desc <span class="text-danger">*</span></label>
                            <textarea required id="sec_result_desc" class="form-control" id="exampleFormControlTextarea1" placeholder="Enter sort description " name="sec_result_desc" rows="2">
                            {{ $old->sec_result_desc }}
                            </textarea>
                        </div>
                    </div>

                    <h4 class="my-2 bg-secondary text-white p-1">Ranking Section</h4>

                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="sec_ranking_title">sec_ranking_title <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="sec_ranking_title" name="sec_ranking_title" placeholder="Enter sec_ranking_title" value="{{ $old->sec_ranking_title }}" data-msg="Please enter header_tittle" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label for="slug" class="form-label">Section Ranking Image</label>

                            @if($old->sec_ranking_image)
                            <div class="mb-2">
                                <img id="preview_sec_ranking" src="{{ asset($old->sec_ranking_image) }}" alt="sec_ranking_image" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                            @endif
                            <div class="mb-2" id="preview_container_sec_ranking" style="display: none;">
                                <img id="preview_sec_ranking" alt="sec_ranking_image" class="img-thumbnail" style="max-width: 200px;">
                            </div>

                            <input type="file" id="sec_ranking_image" name="sec_ranking_image" class="form-control" placeholder="Enter feature picture" onchange="previewImageSecRanking(event)">
                            @error('sec_ranking_image')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="sec_ranking_desc">sec_ranking_desc <span class="text-danger">*</span></label>
                            <textarea required id="sec_ranking_desc" class="form-control" id="exampleFormControlTextarea1" placeholder="Enter sort description " name="sec_ranking_desc" rows="2">
                            {{ $old->sec_ranking_desc }}
                            </textarea>
                        </div>
                    </div>

                    <h4 class="my-2 bg-secondary text-white p-1">Seo Section</h4>

                    <div class="row">
                            <div class="col-md-6 mb-1">
                                <label class="form-label" for="index_status">Index</label>
                                <select class="select2 form-select" id="index_status" name="index_status">
                                    <option value="1" {{ $old->index_status == 1 ? 'selected' : '' }}>Index</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="meta_title">Title(Meta) <span style="color: red;">[NB: %currentyear%  = Current Year]</span></label>
                                <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="Meta Title" value="{{ $old->meta_title}}" data-msg="Please enter meta title" />
                            </div>
                            <div class="col-12 col-sm-12 mb-1">
                                <label class="form-label" for="meta_description">Description (Meta)</label>
                                <textarea class="form-control" id="meta_description" name="meta_description" placeholder="Meta Description"  data-msg="Please enter meta description">{{ $old->meta_description }}</textarea>
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
    CKEDITOR.replace('sec_1_desc');
    CKEDITOR.replace('sec_2_desc');
    CKEDITOR.replace('sec_3_desc');
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
            var output = document.getElementById('preview_sec_1');
            output.src = reader.result;
            document.getElementById('preview_container_sec_1').style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function previewImageSec2(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview_sec_2');
            output.src = reader.result;
            document.getElementById('preview_container_sec_2').style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function previewImageSec3(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview_sec_3');
            output.src = reader.result;
            document.getElementById('preview_container_sec_3').style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function previewImageSecRanking(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview_sec_ranking');
            output.src = reader.result;
            document.getElementById('preview_container_sec_ranking').style.display = 'block';
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

<script>
    $(document).ready(function() {
        var contentSections = $('#content-sections');
        $('#add').click(function() {
            let randomNumber = Math.random();
            var newSection = `<div class="content-section">
                            <div class="row">
                                <div class="col-5 col-sm-5 mb-1">
                                    <label class="form-label" for="accountName">Name</label>
                                    <input type="text" class="form-control" name="solution_name[]" placeholder="Enter content Name">
                                </div>

                                <div class="col-5 col-sm-5 mb-1">
                                    <label class="form-label" for="accountTitle">Title</label>
                                    <input type="text" class="form-control" name="solution_title[]" placeholder="Enter content title">
                                </div>

                                <div class="col-2 col-sm-2 mb-1">
                                    <label class="form-label" for="sequence">Sequence</label>
                                    <input required type="number" class="form-control" name="solution_sequence[]" placeholder="Enter No.">
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 mb-1">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea required name="solution_description[]" id="editor${randomNumber}" class="form-control ckeditor" rows="2" placeholder="Enter blog description"></textarea>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <label for="slug" class="form-label"> Image</label>
                                <input type="file" id="sec_3_image" name="solution_image[]" class="form-control" placeholder="Enter feature picture" onchange="previewImage(event)">
                            </div>



                            <button type="button" class="btn btn-danger mb-1 me-1 remove1" id="remove">Remove</button>
                        </div>`

            contentSections.append(newSection);
            CKEDITOR.replace(`editor${randomNumber}`);
        });


        $(document).on('click', '.remove1', function() {
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
                    $(this).closest('.content-section').remove();
                }
            });

        });
    });
</script>

<script>
    $(document).ready(function() {
        var contentSections = $('#image-sections');
        $('#addImage').click(function() {
            var newSection = ` <div class="image-section">
                            <div class="col-6 col-sm-6 mb-1">
                                <label for="slug" class="form-label"> Image</label>
                                <input type="file" id="sec_3_image" name="slider_image[]" class="form-control" placeholder="Enter feature picture" onchange="previewImage(event)">
                            </div>
                             <div class="col-6 col-sm-6 mb-1">
                                <label for="slug" class="form-label"> Image Alt</label>
                                <input required type="text" id="sec_3_image" name="image_alt[]" class="form-control" placeholder="Enter alter">
                            </div>
                            <button type="button" class="btn btn-danger mb-1 me-1 removeImage">Remove</button>
                        </div>`

            contentSections.append(newSection);
        });


        $(document).on('click', '.removeImage', function() {
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
                    $(this).closest('.image-section').remove();
                }
            });

        });
    });
</script>


@endsection