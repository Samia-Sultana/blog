@extends('layouts/contentLayoutMaster')

@section('title', 'Create SEO')

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
                {{-- <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i data-feather="user" class="font-medium-3 me-50"></i>
                        <span class="fw-bold">General Info</span>
                    </a>
                </li> --}}
                <!-- security -->
                {{-- <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i data-feather="lock" class="font-medium-3 me-50"></i>
                        <span class="fw-bold">Content</span>
                    </a>
                </li> --}}
                <!-- billing and plans -->
                <li class="nav-item ">
                    <a class="nav-link active" href="#">
                        <i data-feather="bookmark" class="font-medium-3 me-50"></i>
                        <span class="fw-bold">SEO</span>
                    </a>
                </li>

            </ul>

            <!-- profile -->
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">SEO</h4>
                </div>
                <div class="card-body py-2 my-25">

                    <!-- form -->
                    <form class="validate-form pt-50" method="POST" action="{{ route('store-seo') }}" enctype="multipart/form-data">
                        @csrf

                        @php
                            $id = request()->segments();
                            $id = end($id);
                        @endphp

                        <input type="hidden" name="blog_id" value="{{ $id }}">


                        <div class="row">
                            <!-- Meta Information Section -->
                            <!-- Index Status -->
                            <div class="col-md-6 mb-1">
                                <label class="form-label" for="index_status">Index</label>
                                <select class="select2 form-select" id="index_status" name="index_status">
                                    <option value="1">Index</option>
                                    <option value="2">No Index</option>
                                </select>
                            </div>
                            <!-- Meta Title -->
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="meta_title">Title(Meta)</label>
                                <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="Meta Title" value="{{ old('meta_title') }}" data-msg="Please enter meta title" />
                            </div>
                            <!-- Meta Description -->
                            <div class="col-12 col-sm-12 mb-1">
                                <label class="form-label" for="meta_description">Description (Meta)</label>
                                <textarea class="form-control" id="meta_description" name="meta_description" placeholder="Meta Description" value="{{ old('meta_description') }}" data-msg="Please enter meta description"></textarea>
                            </div>
                            <!-- End of Meta Information Section -->

                            <!-- Post Links Section -->
                            <div class="col-12 mt-2">
                                <div class="border-bottom">
                                    <h4 class="card-title">Post Links</h4>
                                </div>
                                <div class="my-25" id="post-link-sections">
                                    <!-- Post Link Section Template -->
                                    <div class="post-link-template">
                                        <div class="row">
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="key">Key</label>
                                                <input type="text" class="form-control" name="post_key[]" placeholder="Key" data-msg="Please enter key" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="value">Value</label>
                                                <input type="text" class="form-control" name="post_value[]" placeholder="Value" data-msg="Please enter value" />
                                            </div>
                                            <div class="col-12">
                                                <button type="button" class="btn btn-danger mt-1 me-1 remove" style="display: none;">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End of Post Link Section Template -->
                                </div>
                                <button type="button" class="btn btn-primary mt-1 me-1" id="add-post-link">Add</button>
                            </div>
                            <!-- End of Post Links Section -->

                            <!-- Post Script Section -->
                            <div class="col-12 mt-3">
                                <div class="border-bottom">
                                    <h4 class="card-title">Post Script</h4>
                                </div>
                                <div class="my-25" id="post-script-sections">
                                    <!-- Post Script Section Template -->
                                    <div class="post-script-template">
                                        <div class="row">
                                            <div class="col-12 col-sm-12 mb-1">
                                                <label class="form-label" for="type">Type</label>
                                                <input type="text" class="form-control" name="type[]" placeholder="Type" value="{{ old('type') }}" data-msg="Please enter type" />
                                            </div>
                                            <div class="col-12 col-sm-12 mb-1">
                                                <label class="form-label" for="script">Script</label>
                                                <textarea class="form-control" name="script[]" placeholder="Script" value="{{ old('script') }}" data-msg="Please enter script"></textarea>
                                            </div>
                                            <div class="col-12">
                                                <button type="button" class="btn btn-danger mt-1 me-1 remove" style="display: none;">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End of Post Script Section Template -->
                                </div>
                                <button type="button" class="btn btn-primary mt-1 me-1" id="add-post-script">Add</button>
                            </div>
                            <!-- End of Post Script Section -->

                            <!-- Save and Discard Buttons -->
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary mt-1 me-1">Save changes</button>
                                <button type="reset" class="btn btn-outline-secondary mt-1">Discard</button>
                            </div>
                            <!-- End of -->
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('#add-post-script').click(function() {
            var newSection = $('.post-script-template').clone();
            newSection.removeClass('post-script-template').addClass('post-script-section');
            newSection.find('input[type="text"], textarea').val('');
            $('#post-script-sections').append(newSection);
            newSection.find('.remove').show(); // Show remove button for newly added section
        });

        $(document).on('click', '.remove', function() {
            $(this).closest('.post-script-section').remove();
        });
    });

    $(document).ready(function() {
        $('#add-post-link').click(function() {
            var newSection = $('.post-link-template').clone();
            newSection.removeClass('post-link-template').addClass('post-link-sections');
            newSection.find('input[type="text"], input[type="text"]').val('');
            $('#post-link-sections').append(newSection);
            newSection.find('.remove').show(); // Show remove button for newly added section
        });

        $(document).on('click', '.remove', function() {
            $(this).closest('.post-link-sections').remove();
        });
    });
</script>



@endsection
