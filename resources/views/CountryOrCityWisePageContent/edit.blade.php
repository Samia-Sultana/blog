@extends('layouts/contentLayoutMaster')

@section('title', 'Edit Page')

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

    @if (Session::has('success'))
        <div id="success-alert" class="alert alert-success" style="padding: 15px;">
            {{ Session::get('success') }}
        </div>
    @endif

    @if (session('error'))
        <div id="error-alert" class="alert alert-danger" style="padding: 15px;">
            {{ session('error') }}
        </div>
    @endif

    <div class="row" id="table-hover-row">
        <div class="col-12">
            <div class="card shadow rounded-3">
                <div class="card-body">
                    <form action="{{ route('pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- Use PUT method for updates -->

                        <!-- Page Title -->
                        <fieldset class="border p-3 rounded mb-4">
                            <div class="d-flex justify-content-center align-items-center">
                                <legend style="font-weight: 500" class="w-auto">Page Title</legend>
                            </div>
                            <div class="form-group mb-2">
                                <label for="page_title">Page Title</label>
                                <input type="text" class="form-control" id="page_title" name="page_title" value="{{ $page->page_title }}" placeholder="Enter Page Title">
                            </div>
                            <div class="form-group">
                                <label for="page_url">Page URL</label>
                                <input type="text" class="form-control" id="page_url" name="page_url" value="{{ $page->page_url }}" placeholder="Enter Page URL">
                            </div>
                        </fieldset>

                        <!-- Section 1 -->
                        <fieldset class="border p-3 rounded mb-4">
                            <div class="d-flex justify-content-center align-items-center">
                                <legend style="font-weight: 500" class="w-auto">Section 1 - The SEO Agency</legend>
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 1 - Background Image</label>
                                <input type="file" class="form-control" name="section_1_content_1">
                                @if($page->section_1_content_1)
                                    <img src="{{ asset('storage/' . $page->section_1_content_1) }}" alt="Section 1 Background" class="img-thumbnail mt-2" width="100">
                                @endif
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 2 - Image Url</label>
                                <input type="text" class="form-control" name="section_10_content_1" value="{{$page->section_10_content_1}}" placeholder="Image Url Link">
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 3 - Best SEO Service Company In</label>
                                <input type="text" class="form-control" name="section_1_content_2" value="{{ $page->section_1_content_2 }}" placeholder="Best SEO Service Company In">
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 4 - Description</label>
                                <textarea name="section_1_content_3" class="form-control" placeholder="Description">{{ $page->section_1_content_3 }}</textarea>
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 5 - Monthly organic visitors</label>
                                <input type="text" class="form-control" name="section_1_content_4" value="{{ $page->section_1_content_4 }}" placeholder="Monthly organic visitors">
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 6 - SEO Case Studies</label>
                                <input type="text" class="form-control" name="section_1_content_5" value="{{ $page->section_1_content_5 }}" placeholder="SEO Case Studies">
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 7 - Ecommerce Transations</label>
                                <input type="text" class="form-control" name="section_1_content_6" value="{{ $page->section_1_content_6 }}" placeholder="Ecommerce Transations">
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 8 - Ecommerce Projects</label>
                                <input type="text" class="form-control" name="section_1_content_7" value="{{ $page->section_1_content_7 }}" placeholder="Ecommerce Projects">
                            </div>
                        </fieldset>

                        <!-- Example for Section 2 -->


                        <!-- Section 3 -->
                        <fieldset class="border p-3 rounded mb-4">
                            <div class="d-flex justify-content-center align-items-center">
                                <legend style="font-weight: 500" class="w-auto">Section 2 - Why VISER X is The Best SEO Agency</legend>
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 1 - Why is VISER X the Best SEO Agency in</label>
                                <input type="text" class="form-control" name="section_3_content_1" value="{{ $page->section_3_content_1 }}" placeholder="Why is VISER X the Best SEO Agency in">
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 2 - Description</label>
                                <textarea name="section_3_content_2" class="form-control" placeholder="Description">{{ $page->section_3_content_2 }}</textarea>
                            </div>
                        </fieldset>

                        <!-- Section 4 -->
                        <fieldset class="border p-3 rounded mb-4">
                            <div class="d-flex justify-content-center align-items-center">
                                <legend style="font-weight: 500" class="w-auto">Section 3 - Our SEO Strength</legend>
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 1 - Our SEO Strength</label>
                                <input type="text" class="form-control" name="section_4_content_1" value="{{ $page->section_4_content_1 }}" placeholder="Our SEO Strength">
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 2 - Industry Experts</label>
                                <textarea name="section_4_content_2" class="form-control" placeholder="Industry Experts">{{ $page->section_4_content_2 }}</textarea>
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 3 - Experienced</label>
                                <textarea name="section_4_content_3" class="form-control" placeholder="Experienced">{{ $page->section_4_content_3 }}</textarea>
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 4 - Dedicated Project Manager</label>
                                <textarea name="section_4_content_4" class="form-control" placeholder="Dedicated Project Manager">{{ $page->section_4_content_4 }}</textarea>
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 5 - Dedicated Writers</label>
                                <textarea name="section_4_content_5" class="form-control" placeholder="Dedicated Writers">{{ $page->section_4_content_5 }}</textarea>
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 6 - SEO Reporting</label>
                                <textarea name="section_4_content_6" class="form-control" placeholder="SEO Reporting">{{ $page->section_4_content_6 }}</textarea>
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 7 - Maximize ROI</label>
                                <textarea name="section_4_content_7" class="form-control" placeholder="Maximize ROI">{{ $page->section_4_content_7 }}</textarea>
                            </div>
                        </fieldset>

                        <!-- Section 5 -->
                        {{-- <fieldset class="border p-3 rounded mb-4">
                            <div class="d-flex justify-content-center align-items-center">
                                <legend style="font-weight: 500" class="w-auto">Section 4 - Trusted</legend>
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 1 - Trusted</label>
                                <input type="text" class="form-control" name="section_5_content_1" value="{{ $page->section_5_content_1 }}" placeholder="Trusted..">
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 2 - Description</label>
                                <textarea name="section_5_content_2" class="form-control" placeholder="Description">{{ $page->section_5_content_2 }}</textarea>
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 3 - Location</label>
                                <input type="text" class="form-control" name="section_5_content_3" value="{{ $page->section_5_content_3 }}" placeholder="Location">
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 4 - Phone</label>
                                <input type="text" class="form-control" name="section_5_content_4" value="{{ $page->section_5_content_4 }}" placeholder="Phone">
                            </div>
                        </fieldset> --}}

                        <!-- Section 6 -->
                        <fieldset class="border p-3 rounded mb-4">
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <legend style="font-weight: 500" class="w-auto">Section 4 - Case Studies</legend>
                                </div>
                            </div>
                            <div id="case-studies-container">
                                @php
                                    $caseStudies = json_decode($page->section_6_case_studies ?? '[]', true);
                                @endphp

                                @foreach($caseStudies as $index => $caseStudy)
                                <div class="case-study-section form-group mb-4 p-3 border rounded">
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-danger btn-sm remove-case-study" onclick="removeCaseStudy(this)">Remove</button>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label>Section 6 Slider Image</label>
                                        @if(!empty($caseStudy['image']))
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/'.$caseStudy['image']) }}" class="img-thumbnail" style="max-height: 100px;">
                                            <input type="hidden" name="existing_section_6_slider_image[{{ $index }}]" value="{{ $caseStudy['image'] }}">
                                        </div>
                                        @endif
                                        <input type="file" class="form-control mb-1" name="section_6_slider_image[]">
                                        <label>Section 6 Slider Heading</label>
                                        <input type="text" class="form-control mb-1" name="section_6_slider_heading[]"
                                               value="{{ old('section_6_slider_heading.'.$index, $caseStudy['heading'] ?? '') }}"
                                               placeholder="Section 6 slider heading">
                                        <label>Section 6 Slider Description</label>
                                        <textarea name="section_6_slider_description[]" class="form-control mb-1"
                                                  placeholder="Section 6 description">{{ old('section_6_slider_description.'.$index, $caseStudy['description'] ?? '') }}</textarea>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-primary mt-2" onclick="addCaseStudy()">Add Another Case Study</button>
                        </fieldset>

                        <!-- Section 7 -->
                        {{-- <fieldset class="border p-3 rounded mb-4">
                            <div class="d-flex justify-content-center align-items-center">
                                <legend style="font-weight: 500" class="w-auto">Section 6 - SEO Service Packages</legend>
                            </div>
                            <div class="form-group mb-2">
                                <label>Section 7 Content 1</label>
                                <input type="text" class="form-control" name="section_7_content_1" value="{{ $page->section_7_content_1 }}" placeholder="Section 7 Content 1">
                            </div>
                            <div class="form-group mb-2">
                                <label>Section 7 Content 2</label>
                                <input type="text" class="form-control" name="section_7_content_2" value="{{ $page->section_7_content_2 }}" placeholder="Section 7 Content 2">
                            </div>
                            <div class="form-group mb-2">
                                <label>Section 7 Content 3</label>
                                <input type="text" class="form-control" name="section_7_content_3" value="{{ $page->section_7_content_3 }}" placeholder="Section 7 Content 3">
                            </div>
                            <div class="form-group mb-2">
                                <label>Section 7 Content 4</label>
                                <input type="text" class="form-control" name="section_7_content_4" value="{{ $page->section_7_content_4 }}" placeholder="Section 7 Content 4">
                            </div>
                            <div class="form-group mb-2">
                                <label>Section 7 Content 5</label>
                                <input type="text" class="form-control" name="section_7_content_5" value="{{ $page->section_7_content_5 }}" placeholder="Section 7 Content 5">
                            </div>
                            <div class="form-group mb-2">
                                <label>Section 7 Content 6</label>
                                <input type="text" class="form-control" name="section_7_content_6" value="{{ $page->section_7_content_6 }}" placeholder="Section 7 Content 6">
                            </div>
                            <div class="form-group mb-2">
                                <label>Section 7 Content 7</label>
                                <input type="text" class="form-control" name="section_7_content_7" value="{{ $page->section_7_content_7 }}" placeholder="Section 7 Content 7">
                            </div>
                            <div class="form-group mb-2">
                                <label>Section 7 Content 8</label>
                                <input type="text" class="form-control" name="section_7_content_8" value="{{ $page->section_7_content_8 }}" placeholder="Section 7 Content 8">
                            </div>
                        </fieldset> --}}

                        <!-- Section 8 -->
                        <fieldset class="border p-3 rounded mb-4">
                            <div class="d-flex justify-content-center align-items-center">
                                <legend style="font-weight: 500" class="w-auto">Section 5 - Earn more revenue</legend>
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 1 - Image</label>
                                <input type="file" class="form-control" name="section_8_content_1">
                                @if($page->section_8_content_1)
                                    <img src="{{ asset('storage/' . $page->section_8_content_1) }}" alt="Section 1 Background" class="img-thumbnail mt-2" width="100">
                                @endif
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 2 - Earn more revenue with NYC SEO services</label>
                                <input type="text" class="form-control" name="section_8_content_2" value="{{ $page->section_8_content_2 }}" placeholder="Earn more revenue with NYC SEO services">
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 3 - Description</label>
                                <textarea name="section_8_content_3" class="form-control" placeholder="Description">{{ $page->section_8_content_3 }}</textarea>
                            </div>
                        </fieldset>

                        <!-- Section 9 -->
                        <fieldset class="border p-3 rounded mb-4">
                            <div class="d-flex justify-content-center align-items-center">
                                <legend style="font-weight: 500" class="w-auto">Section 6 - Get Started with SEO Company</legend>
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 1 - Image</label>
                                <input type="file" class="form-control" name="section_9_content_1" placeholder="Section 9 Content 1">
                                @if($page->section_9_content_1)
                                    <img src="{{ asset('storage/' . $page->section_9_content_1) }}" alt="Section 1 Background" class="img-thumbnail mt-2" width="100">
                                @endif
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 2 - Get Started with</label>
                                <input type="text" class="form-control" name="section_9_content_2" value="{{ $page->section_9_content_2 }}" placeholder="Get Started with">
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 3 - Description</label>
                                <textarea name="section_9_content_3" class="form-control" placeholder="Description">{{ $page->section_9_content_3 }}</textarea>
                            </div>
                        </fieldset>

                        <fieldset class="border p-3 rounded mb-4">
                            <div class="d-flex justify-content-center align-items-center">
                                <legend style="font-weight: 500" class="w-auto">Section 7 - Get Start With</legend>
                            </div>
                            <div class="form-group">
                                <label>Content 1 - Get Start With</label>
                                <input type="text" class="form-control" name="section_2_content_1" value="{{ $page->section_2_content_1 }}" placeholder="Get Start With">
                            </div>
                        </fieldset>

                        <!-- Section 10 -->
                        {{-- <fieldset class="border p-3 rounded mb-4">
                            <div class="d-flex justify-content-center align-items-center">
                                <legend style="font-weight: 500" class="w-auto">Section 10 - Get Started with</legend>
                            </div>
                            <div class="form-group">
                                <label>Section 10 Content 1</label>
                                <input type="text" class="form-control" name="section_10_content_1" value="{{ $page->section_10_content_1 }}" placeholder="Section 10 Content 1">
                            </div>
                        </fieldset> --}}

                        <fieldset class="border p-3 rounded mb-4">
                            <div class="d-flex justify-content-center align-items-center">
                                <legend style="font-weight: 500" class="w-auto">Section 8 - FAQ</legend>
                            </div>
                            <div class="form-group mb-2">
                                <label>Content 1 FAQ Question</label>
                                <input type="text" class="form-control" name="section_11_content_1_faq" placeholder="FAQ Question" value="{{ $page->section_11_content_1_faq }}">
                            </div>
                            <div class="form-group">
                                <label>Content 1 FAQ Answer</label>
                                <textarea name="section_11_content_2_faq" class="form-control" placeholder="Enter FAQ Question description">{{ $page->section_11_content_2_faq }}</textarea>
                            </div>
                        </fieldset>

                        <fieldset class="border p-3 rounded mb-4">
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <legend style="font-weight: 500" class="w-auto">Seo</legend>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label class="form-label" for="index">Index</label>
                                <select class="select2 form-select" id="index" name="index">
                                    <option value="1" {{ $page->index == 1 ? 'selected' : '' }}>Index</option>
                                    <option value="2" {{ $page->index == 2 ? 'selected' : '' }}>No Index</option>
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label>Meta</label>
                                <textarea name="meta" class="form-control" placeholder="Enter meta description">{{ $page->meta }}</textarea>
                            </div>
                            <div class="form-group mb-2">
                                <label>Link</label>
                                <textarea name="link" class="form-control" placeholder="Enter link description">{{ $page->link }}</textarea>
                            </div>
                            <div class="form-group mb-2">
                                <label>Script</label>
                                <textarea name="script" class="form-control" placeholder="Enter link description">{{ $page->script }}</textarea>
                            </div>
                        </fieldset>

                        <!-- Submit Button -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-lg px-5">Update</button>
                        </div>
                    </form>
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

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $("#success-alert").alert('close');
            }, 3000);
        });
    </script>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $("#error-alert").alert('close');
            }, 3000);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        function confirmDelete(blogCategoryId) {
            document.getElementById('delete-blog-category-id').value = blogCategoryId;
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
                    submitForm();
                }
            });
        }

        function submitForm() {
            document.getElementById('deleteForm').submit();
        }
    </script>

    <script>
        document.getElementById('page_url').addEventListener('input', function() {
            let title = this.value;
            let slug = title.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')         // Remove special characters
                .replace(/\s+/g, '-')                 // Replace spaces with hyphens
                .replace(/-+/g, '-');                 // Replace multiple hyphens with a single hyphen

            document.getElementById('page_url').value = slug;
        });
    </script>


    <script>
        // Counter to keep track of added sections
        let caseStudyCounter = {{ count($caseStudies) }};

        function addCaseStudy() {
            caseStudyCounter++;

            const container = document.getElementById('case-studies-container');
            const newSection = document.createElement('div');
            newSection.className = 'case-study-section form-group mb-4 p-3 border rounded';
            newSection.innerHTML = `
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm remove-case-study" onclick="removeCaseStudy(this)">Remove</button>
                </div>
                <div class="form-group mb-2">
                    <label>Section 6 Slider Image</label>
                    <input type="file" class="form-control mb-1" name="section_6_slider_image[]">
                    <label>Section 6 Slider Heading</label>
                    <input type="text" class="form-control mb-1" name="section_6_slider_heading[]" placeholder="Section 6 slider heading">
                    <label>Section 6 Slider Description</label>
                    <textarea name="section_6_slider_description[]" class="form-control mb-1" placeholder="Section 6 description"></textarea>
                </div>
            `;

            container.appendChild(newSection);
        }

        function removeCaseStudy(button) {
            const sectionToRemove = button.closest('.case-study-section');
            sectionToRemove.remove();
        }
    </script>
@endsection
