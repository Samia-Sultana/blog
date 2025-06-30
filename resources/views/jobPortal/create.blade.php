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
                <form class="validate-form pt-50" method="POST" action="{{ route('job.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter title" value="{{ old('title') }}" data-msg="Please enter title" />
                        </div>
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="position_id">Job Position <span class="text-danger">*</span></label>
                            <select required class="form-control" id="position_id" name="position_id" data-msg="Please select a job position">
                                <option value="">Select Position</option>
                                @foreach($positionLabels as $label)
                                    <option value="{{ $label->id }}" {{ old('position_id') == $label->id ? 'selected' : '' }}>
                                        {{ $label->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="accountLastName">Slug <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="slug" name="slug" placeholder="slug" value="{{ old('slug') }}" data-msg="Please enter slug" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="job_type">Job type <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="job_type" name="job_type" placeholder="Enter job type" value="{{ old('job_type') }}" data-msg="Please enter job_type" />
                        </div>
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="salary">Salary <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="salary" name="salary" placeholder="Enter salary" value="{{ old('salary') }}" data-msg="Please enter salary" />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="no_of_vacancy">No of vacancy <span class="text-danger">*</span></label>
                            <input required type="number" class="form-control" id="no_of_vacancy" name="no_of_vacancy" placeholder="Enter no of vacancy" value="{{ old('no_of_vacancy') }}" data-msg="Please enter no_of_vacancy" />
                        </div>


                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="deadline">Deadline <span class="text-danger">*</span></label>
                            <input required type="date" class="form-control" id="deadline" name="deadline" placeholder="Enter deadline" value="{{ old('deadline') }}" data-msg="Please enter deadline" />
                        </div>
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="address">Address <span class="text-danger">*</span></label>
                            <textarea required class="form-control" id="exampleFormControlTextarea1" name="address" rows="3">{{ $old->address }}</textarea>
                        </div>


                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="about_company">About company <span class="text-danger">*</span></label>
                            <textarea required class="form-control" id="exampleFormControlTextarea1" name="about_company" rows="3">{{ $old->about_company }}</textarea>
                        </div>

                        {{-- <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="address">Address <span class="text-danger">*</span></label>
                            <textarea required class="form-control" id="exampleFormControlTextarea1" name="address" rows="3">{{ $old->address }}</textarea>
                        </div> --}}


                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="educations">Educations</label>
                            <div id="educations-container">
                                <div class="d-flex educations-row" id="first-row">
                                    <input type="text" class="form-control" name="educations[]" placeholder="Enter employment statuses" data-msg="Please enter educations" />
                                </div>
                            </div>
                            <button type="button" id="add-more-educations" class="btn btn-sm btn-primary mt-1">Add More</button>
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="experiences">Experiences</label>
                            <div id="experiences-container">
                                <div class="d-flex experiences-row" id="first-row">
                                    <input type="text" class="form-control" name="experiences[]" placeholder="Enter employment statuses" data-msg="Please enter experiences" />
                                </div>
                            </div>
                            <button type="button" id="add-more-experiences" class="btn btn-sm btn-primary mt-1">Add More</button>
                        </div>



                        <!-- <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="employment_statuses">Employment Statuses</label>
                            <div id="employment_statuses-container">
                                <div class="d-flex employment_statuses-row" id="first-row">
                                    <input type="text" class="form-control" name="employment_statuses[]" placeholder="Enter employment statuses" data-msg="Please enter employment_statuses" />
                                </div>
                            </div>
                            <button type="button" id="add-more-employment_statuses" class="btn btn-sm btn-primary mt-1">Add More</button>
                        </div> -->
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="employment_statuses">Employment Statuses</label>
                            <div id="employment_statuses-container">
                                <div class="employment_statuses-row" id="first-row">
                                    <input type="text" class="form-control" name="employment_statuses[]" value="{{json_decode($old->employment_statuses)[0]}}" placeholder="Enter employment status" data-msg="Please enter employment status" />


                                </div>

                                @foreach(array_slice(json_decode($old->employment_statuses),1) as $status)
                                <div class="d-flex employment_statuses-container">
                                    <input type="text" class="form-control mt-1" name="employment_statuses[]" value="{{ $status }}" placeholder="Enter employment status" data-msg="Please enter employment status" />
                                    <button type="button" class="btn btn-sm btn-primary mt-1 ms-1 remove-row">Remove</button>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" id="add-more-employment_statuses" class="btn btn-sm btn-primary mt-1">Add More</button>
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="responsibilities">Responsibilities</label>
                            <div id="responsibilities-container">
                                <div class="d-flex responsibilities-row" id="first-row">
                                    <input type="text" class="form-control" name="responsibilities[]" placeholder="Enter responsibilities" data-msg="Please enter responsibilities" />
                                </div>
                            </div>
                            <button type="button" id="add-more-responsibilities" class="btn btn-sm btn-primary mt-1">Add More</button>
                        </div>


                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="requirements">Requirements</label>
                            <div id="requirements-container">
                                <div class="d-flex requirements-row" id="first-row">
                                    <input type="text" class="form-control" name="requirements[]" placeholder="Enter requirements" data-msg="Please enter requirements" />
                                </div>
                            </div>
                            <button type="button" id="add-more" class="btn btn-sm btn-primary mt-1">Add More</button>
                        </div>


                     <!--   <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="benefits">Benefits</label>
                            <div id="benefits-container">
                                <div class="d-flex benefits-row" id="first-row">
                                    <input type="text" class="form-control" name="benefits[]" placeholder="Enter benefits" data-msg="Please enter benefits" />
                                </div>
                            </div>
                            <button type="button" id="add-more-benefits" class="btn btn-sm btn-primary mt-1">Add More</button>
                        </div> -->

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="benefits">Benefits</label>
                            <div id="benefits-container">
                                <div class="benefits-row" id="first-row">
                                    <input type="text" class="form-control" name="benefits[]" value="{{json_decode($old->benefits)[0]}}" placeholder="Enter employment status" data-msg="Please enter employment status" />


                                </div>

                                @foreach(array_slice(json_decode($old->benefits),1) as $status)
                                <div class="d-flex benefits-container">
                                    <input type="text" class="form-control mt-1" name="benefits[]" value="{{ $status }}" placeholder="Enter employment status" data-msg="Please enter employment status" />
                                    <button type="button" class="btn btn-sm btn-primary mt-1 ms-1 remove-row">Remove</button>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" id="add-more-benefits" class="btn btn-sm btn-primary mt-1">Add More</button>
                        </div>



                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="image">Image <span class="text-danger">*</span></label>
                            <input required type="file" class="form-control" id="exampleFormControlTextarea1" name="image">
                        </div>

                            {{-- <div class="col-12 col-sm-12 mb-1">
                                <label class="form-label" for="description">Job Description</label>
                                <textarea required name="description" class="form-control ckeditor" rows="2" placeholder="Enter blog description"></textarea>
                            </div> --}}

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


<script>


//Start " Add More For educations "
document.getElementById('add-more-educations').addEventListener('click', function() {
        var container = document.getElementById('educations-container');
        var newRow = document.createElement('div');
        newRow.classList.add('d-flex', 'educations-row');
        newRow.innerHTML = `
            <input type="text" class="form-control mt-1" name="educations[]" placeholder="Enter employment statuses" data-msg="Please enter educations" />
            <button type="button" class="btn btn-sm btn-primary mt-1 ms-1 remove-row">Remove</button>
        `;
        container.appendChild(newRow);
        showRemoveButtons();
    });

    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-row')) {
            var row = e.target.parentNode;
            var container = document.getElementById('educations-container');
            if (container.children.length > 1) {
                row.remove();
                showRemoveButtons();
            }
        }
    });

    function showRemoveButtons() {
        var rows = document.querySelectorAll('.educations-row');
        document.getElementById('first-row').querySelector('.remove-row').classList.add('d-none');
        if (rows.length === 1) {
            rows[0].querySelector('.remove-row').classList.add('d-none');
        } else {
            rows.forEach(function(row) {
                row.querySelector('.remove-row').classList.remove('d-none');
            });
        }
    }

    //End " Add More For educations "


     //Start " Add More For experiences "
     document.getElementById('add-more-experiences').addEventListener('click', function() {
        var container = document.getElementById('experiences-container');
        var newRow = document.createElement('div');
        newRow.classList.add('d-flex', 'experiences-row');
        newRow.innerHTML = `
            <input type="text" class="form-control mt-1" name="experiences[]" placeholder="Enter employment statuses" data-msg="Please enter experiences" />
            <button type="button" class="btn btn-sm btn-primary mt-1 ms-1 remove-row">Remove</button>
        `;
        container.appendChild(newRow);
        showRemoveButtons();
    });

    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-row')) {
            var row = e.target.parentNode;
            var container = document.getElementById('experiences-container');
            if (container.children.length > 1) {
                row.remove();
                showRemoveButtons();
            }
        }
    });

    function showRemoveButtons() {
        var rows = document.querySelectorAll('.experiences-row');
        document.getElementById('first-row').querySelector('.remove-row').classList.add('d-none');
        if (rows.length === 1) {
            rows[0].querySelector('.remove-row').classList.add('d-none');
        } else {
            rows.forEach(function(row) {
                row.querySelector('.remove-row').classList.remove('d-none');
            });
        }
    }

    //End " Add More For experiences "


    //Start " Add More For employment_statuses "
    document.getElementById('add-more-employment_statuses').addEventListener('click', function() {
        var container = document.getElementById('employment_statuses-container');
        var newRow = document.createElement('div');
        newRow.classList.add('d-flex', 'employment_statuses-row');
        newRow.innerHTML = `
            <input type="text" class="form-control mt-1" name="employment_statuses[]" placeholder="Enter employment statuses" data-msg="Please enter employment_statuses" />
            <button type="button" class="btn btn-sm btn-primary mt-1 ms-1 remove-row">Remove</button>
        `;
        container.appendChild(newRow);
        showRemoveButtons();
    });

    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-row')) {
            var row = e.target.parentNode;
            var container = document.getElementById('employment_statuses-container');
            if (container.children.length > 1) {
                row.remove();
                showRemoveButtons();
            }
        }
    });

    function showRemoveButtons() {
        var rows = document.querySelectorAll('.employment_statuses-row');
        document.getElementById('first-row').querySelector('.remove-row').classList.add('d-none');
        if (rows.length === 1) {
            rows[0].querySelector('.remove-row').classList.add('d-none');
        } else {
            rows.forEach(function(row) {
                row.querySelector('.remove-row').classList.remove('d-none');
            });
        }
    }

    //End " Add More For employment_statuses "

    //Start " Add More For responsibilities "
    document.getElementById('add-more-responsibilities').addEventListener('click', function() {
        var container = document.getElementById('responsibilities-container');
        var newRow = document.createElement('div');
        newRow.classList.add('d-flex', 'responsibilities-row');
        newRow.innerHTML = `
            <input type="text" class="form-control mt-1" name="responsibilities[]" placeholder="Enter responsibilities" data-msg="Please enter responsibilities" />
            <button type="button" class="btn btn-sm btn-primary mt-1 ms-1 remove-row">Remove</button>
        `;
        container.appendChild(newRow);
        showRemoveButtons();
    });

    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-row')) {
            var row = e.target.parentNode;
            var container = document.getElementById('responsibilities-container');
            if (container.children.length > 1) {
                row.remove();
                showRemoveButtons();
            }
        }
    });

    function showRemoveButtons() {
        var rows = document.querySelectorAll('.responsibilities-row');
        document.getElementById('first-row').querySelector('.remove-row').classList.add('d-none');
        if (rows.length === 1) {
            rows[0].querySelector('.remove-row').classList.add('d-none');
        } else {
            rows.forEach(function(row) {
                row.querySelector('.remove-row').classList.remove('d-none');
            });
        }
    }

    //End " Add More For Responsibilities "

    // Add More For Requirements Start
    document.getElementById('add-more').addEventListener('click', function() {
        var container = document.getElementById('requirements-container');
        var newRow = document.createElement('div');
        newRow.classList.add('d-flex', 'requirements-row');
        newRow.innerHTML = `
            <input type="text" class="form-control mt-1" name="requirements[]" placeholder="Enter requirements" data-msg="Please enter requirements" />
            <button type="button" class="btn btn-sm btn-primary mt-1 ms-1 remove-row">Remove</button>
        `;
        container.appendChild(newRow);
        showRemoveButtons();
    });

    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-row')) {
            var row = e.target.parentNode;
            var container = document.getElementById('requirements-container');
            if (container.children.length > 1) {
                row.remove();
                showRemoveButtons();
            }
        }
    });

    function showRemoveButtons() {
        var rows = document.querySelectorAll('.requirements-row');
        document.getElementById('first-row').querySelector('.remove-row').classList.add('d-none');
        if (rows.length === 1) {
            rows[0].querySelector('.remove-row').classList.add('d-none');
        } else {
            rows.forEach(function(row) {
                row.querySelector('.remove-row').classList.remove('d-none');
            });
        }
    }

    // Add More For Requirements End


    //Start " Add More For benefits "
    document.getElementById('add-more-benefits').addEventListener('click', function() {
        var container = document.getElementById('benefits-container');
        var newRow = document.createElement('div');
        newRow.classList.add('d-flex', 'benefits-row');
        newRow.innerHTML = `
            <input type="text" class="form-control mt-1" name="benefits[]" placeholder="Enter benefits" data-msg="Please enter benefits" />
            <button type="button" class="btn btn-sm btn-primary mt-1 ms-1 remove-row">Remove</button>
        `;
        container.appendChild(newRow);
        showRemoveButtons();
    });

    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-row')) {
            var row = e.target.parentNode;
            var container = document.getElementById('benefits-container');
            if (container.children.length > 1) {
                row.remove();
                showRemoveButtons();
            }
        }
    });

    function showRemoveButtons() {
        var rows = document.querySelectorAll('.benefits-row');
        document.getElementById('first-row').querySelector('.remove-row').classList.add('d-none');
        if (rows.length === 1) {
            rows[0].querySelector('.remove-row').classList.add('d-none');
        } else {
            rows.forEach(function(row) {
                row.querySelector('.remove-row').classList.remove('d-none');
            });
        }
    }

    //End " Add More For benefits "
</script>


@endsection
