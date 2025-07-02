@extends('layouts/contentLayoutMaster')

@section('title', 'Create Content')

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
        <ul class="nav nav-pills mb-2">
            <!-- Account -->
            {{-- later i'll use it as updte --}}
            {{-- <li class="nav-item">
                    <a class="nav-link" href="{{ route('create-blog') }}">
            <i data-feather="user" class="font-medium-3 me-50"></i>
            <span class="fw-bold">General Info</span>
            </a>
            </li> --}}
            <!-- security -->
            <li class="nav-item">
                <a class="nav-link active" href="#">
                    <i data-feather="lock" class="font-medium-3 me-50"></i>
                    <span class="fw-bold">Content</span>
                </a>
            </li>
            <!-- billing and plans -->
            {{-- <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i data-feather="bookmark" class="font-medium-3 me-50"></i>
                        <span class="fw-bold">SEO</span>
                    </a>
                </li> --}}

        </ul>

        <!-- profile -->

        {{-- <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title
                    ">Content</h4>
                </div>
            </div> --}}

        <div class="card">
            <div class="card-header border-bottom">
                <h4 class="card-title">Content Input</h4>
            </div>
            <div class="card-body py-2 my-25">



                <!-- form -->
                <form class="validate-form pt-50" method="POST" action="{{ route('create-content') }}" enctype="multipart/form-data">
                    @csrf

                    @php
                    $id = request()->segments();
                    $id = end($id);
                    @endphp

                    <input type="hidden" name="blog_id" value="{{ $id }}">

                    <div id="content-sections">
                        <div class="content-section">
                            <div class="row">
                                <div class="col-10 col-sm-10 mb-1">
                                    <label class="form-label" for="accountTitle">Title</label>
                                    <input type="text" class="form-control" name="contentTitle[]" placeholder="Enter content title">
                                </div>

                                <div class="col-2 col-sm-2 mb-1">
                                    <label class="form-label" for="sequence">Sequence</label>
                                    <input required type="number" class="form-control" name="sequence[]" placeholder="Enter No.">
                                </div>

                            </div>



                            <div class="row">
                                <div class="col-12 col-sm-12 mb-1">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea required name="description[]" class="form-control ckeditor" rows="2" placeholder="Enter blog description"></textarea>
                                </div>
                            </div>
                            <button type="button" class="btn btn-danger mb-1 me-1 remove">Remove</button>
                        </div>

                    </div>

                    <div class="col-12">
                        <button type="button" class="btn btn-primary mt-1 me-1" id="add">Add</button>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="//cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    $(document).ready(function() {
        var contentSections = $('#content-sections');

        $('#add').click(function() {

            let randomNumber = Math.random();

            var newSection = `<div class="content-section">
                                    <div class="row">
                                        <div class="col-10 col-sm-10 mb-1">
                                            <label class="form-label" for="accountTitle">Title</label>
                                            <input type="text" class="form-control" name="contentTitle[]" placeholder="Enter content title">
                                        </div>

                                        <div class="col-2 col-sm-2 mb-1">
                                            <label class="form-label" for="sequence">Sequence</label>
                                            <input required type="number" class="form-control" name="sequence[]" placeholder="Enter No.">
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-12 mb-1">
                                            <label class="form-label" for="description">Description</label>
                                            <textarea required id="editor${randomNumber}" name="description[]" class="form-control ckeditor" rows="2" placeholder="Enter blog description"></textarea>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-danger mb-1 me-1 remove">Remove</button>
                              </div>`

            contentSections.append(newSection);
            CKEDITOR.replace(`editor${randomNumber}`);


            // Initialize CKEditor for the newly added textarea
            // CKEDITOR.replace(newSection.find('textarea')[0]);
        });

        $(document).on('click', '.remove', function() {
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
