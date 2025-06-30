@extends('layouts/contentLayoutMaster')

@section('title', 'AI SEO Pages')

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
    <style>
        .table > :not(caption) > * > * {
            background-color: white !important;
        }

        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            white-space: nowrap;
        }

        .table-responsive table {
            width: 100%;
            min-width: 1200px;
            /* force horizontal scroll if table is large */
        }

        .sticky-header-row {
            position: sticky;
            top: 0;
            z-index: 20;
            background-color: white;
        }

        /* Sticky columns */
        .sticky-col {
            position: sticky;
            background-color: white !important;
        }

        .sticky-col-1 {
            left: 0;
            z-index: 30; /* Higher than header row */
        }

        .sticky-col-2 {
            left: 60px;
            z-index: 25;
        }

        .sticky-col-3 {
            left: 120px;
            z-index: 25;
        }

        /* Ensure body sticky columns are below header sticky columns */
        tbody .sticky-col {
            z-index: 15;
        }


    </style>


    <style>
        #scheduleGraphModal .modal-dialog {
            max-width: 45vw;
            margin: 1.75rem auto;
        }

        #scheduleGraphModal .modal-content {
            width: 100%;
        }

        #chartWrapper {
            overflow-x: auto;
            white-space: nowrap;
        }
    </style>

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
            <div class="card">

                <div class="card-body">
                    <div class="">
                        {{-- Search Form Section --}}
                        <form method="GET" action="{{ route('ai-seo-pages') }}"
                            class="d-flex justify-content-between align-items-center">
                            {{-- Location search --}}
                            <div>
                                <label for="title_query" class="form-label mb-1">Page Title</label>
                                <input type="text" name="title_query" value="{{ request('title_query') }}"
                                    class="form-control" id="title_query" placeholder="Search by Title"
                                    style="max-width: 220px;">
                            </div>
                            {{-- Location search --}}
                            <div>
                                <label for="query" class="form-label mb-1">Location</label>
                                <input type="text" name="query" value="{{ request('query') }}" class="form-control"
                                    id="query" placeholder="Search by Country or City" style="max-width: 220px;">
                            </div>

                            <div>
                                <label for="last_published_index_date" class="form-label mb-1">Last Published/Indexed</label>
                                <input type="date" name="last_published_index_date" value="{{ request('last_published_index_date') }}"
                                    class="form-control" id="last_published_index_date" style="max-width: 180px;">
                            </div>

                            {{-- Created At (Date Range) --}}
                            <div>
                                <label for="start_date" class="form-label mb-1">From Date</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}"
                                    class="form-control" id="start_date" style="max-width: 180px;">
                            </div>

                            <div>
                                <label for="end_date" class="form-label mb-1">To Date</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}"
                                    class="form-control" id="end_date" style="max-width: 180px;">
                            </div>

                            {{-- Index Filter --}}
                            <div>
                                <label for="index" class="form-label mb-1">Index Status</label>
                                <select name="index" class="form-select" id="index" style="max-width: 160px;">
                                    <option value="">All</option>
                                    <option value="1" {{ request('index') == 1 ? 'selected' : '' }}>Index</option>
                                    <option value="2" {{ request('index') == 2 ? 'selected' : '' }}>No Index</option>
                                </select>
                            </div>

                            {{-- Published Filter --}}
                            <div>
                                <label for="published" class="form-label mb-1">Publish Status</label>
                                <select name="published" class="form-select" id="published" style="max-width: 180px;">
                                    <option value="">All</option>
                                    <option value="1" {{ request('published') == 1 ? 'selected' : '' }}>Published
                                    </option>
                                    <option value="2" {{ request('published') == 2 ? 'selected' : '' }}>Unpublished
                                    </option>
                                </select>
                            </div>

                            {{-- Search & Clear Buttons --}}
                            <div class="align-self-end d-flex gap-2">
                                <button type="submit" class="btn btn-outline-primary">Search</button>
                                @if (request()->hasAny(['title_query', 'query', 'start_date', 'end_date', 'index', 'published', 'last_published_index_date']))
                                    <a href="{{ route('ai-seo-pages') }}" class="btn btn-outline-secondary">Clear</a>
                                @endif
                            </div>
                        </form>

                        {{-- Action Buttons Section (right side) --}}
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-1">
                        <div>
                            @if (request()->hasAny(['title_query', 'query', 'start_date', 'end_date', 'index', 'published', 'last_published_index_date']))
                                <span class="text-muted"><strong>Total Filtered Count:</strong> {{ $data->total() }}</span>
                            @else
                                <span class="text-muted"><strong>Total Count:</strong> {{ $data->total() }}</span>
                            @endif
                        </div>
                        <div class="d-flex gap-2 ms-auto">
                            <div id="bulk-action-dropdown" class="dropdown" style="display: none;">
                                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="bulkActionButton"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="bulkActionButton">
                                    <li><a class="dropdown-item" href="#" onclick="bulkDelete()">Delete
                                            Selected</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="bulkSetIndex(1)">Add Index Tag</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="bulkSetIndex(2)">Remove Index Tag</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="bulkSetPublished(1)">Publish Now</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="bulkSetPublished(2)">Unpublish Now</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="bulkSetPublishedJob()">Schedule Publishing</a></li>
                                </ul>
                            </div>


                            <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal"
                                data-bs-target="#indexGraphModal">
                                View Index Request Or Published Graph
                            </button>

                            <button type="button" class="btn btn-outline-info" data-bs-toggle="modal"
                                data-bs-target="#scheduleGraphModal">
                                View Schedule Graph
                            </button>

                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                                data-bs-target="#uploadModal">
                                Upload File
                            </button>

                            <a href="{{ route('pages.create') }}" class="btn btn-outline-primary">Add Pages</a>

                            <form method="POST" action="{{ route('ai-page.export') }}">
                                @csrf
                                {{-- Preserve filter inputs --}}
                                @foreach (request()->all() as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach

                                <button type="submit" class="btn btn-primary waves-effect waves-float waves-light">
                                    Export
                                </button>
                            </form>
                        </div>
                    </div>
                </div>



                <div class="table-responsive" style="max-height: 700px; overflow-y: auto;">
                    <table class="table table-hover">
                        <thead>
                            <tr class="sticky-header-row">
                                <th class="sticky-col sticky-col-1">
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th class="sticky-col sticky-col-2">SL</th>
                                <th class="sticky-col sticky-col-3">Page Title</th>
                                <th class="sticky-col">Background Status</th>
                                <th class="sticky-col">AI Model</th>
                                <th class="sticky-col">Locations</th>
                                <th class="sticky-col">Index</th>
                                <th class="sticky-col">Published</th>
                                <th class="sticky-col">Scheduled At</th>
                                <th class="sticky-col">Last Published/Indexed</th>
                                <th class="sticky-col">Created By</th>
                                <th class="sticky-col">
                                    <a href="{{ route(
                                        'ai-seo-pages',
                                        array_merge(request()->except('page'), [
                                            'sort_by' => 'created_at',
                                            'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'created_at' ? 'desc' : 'asc',
                                        ]),
                                    ) }}"
                                        class="text-decoration-none text-dark">
                                        Created At
                                        @php
                                            $sortBy = request('sort_by', 'created_at');
                                            $sortOrder = request('sort_order', 'desc');
                                        @endphp
                                        @if ($sortBy === 'created_at')
                                            {!! $sortOrder === 'asc' ? '▲' : '▼' !!}
                                        @endif
                                    </a>
                                </th>
                                <th class="sticky-col">Updated By</th>
                                <th class="sticky-col">Updated At</th>
                                <th class="sticky-col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $page)
                                <tr>
                                    <td class="sticky-col sticky-col-1">
                                        <input type="checkbox" class="page-checkbox" value="{{ $page->id }}"
                                            name="selected_pages[]">
                                    </td>
                                    <td class="sticky-col sticky-col-2">{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                                    <td class="sticky-col sticky-col-3">

                                        <a href="{{ route('pages.edit', $page->id) }}">
                                            {{ $page->page_title }}
                                        </a>
                                    </td>

                                    <td>
                                        @if ($page->status == 'Done')
                                            <span class="badge rounded-pill bg-success">Done</span>
                                        @elseif ($page->status == 'Processing')
                                            <span class="badge rounded-pill bg-warning text-dark">Processing</span>
                                        @elseif ($page->status == 'Pending')
                                            <span class="badge rounded-pill bg-secondary">Pending</span>
                                        @else
                                            <span class="badge rounded-pill bg-info text-dark">{{ $page->status }}</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $page->ai_model ?? 'Manual' }}
                                    </td>

                                    <td>
                                        {{ $page->locations }}
                                    </td>


                                    <td>
                                        @if ($page->index == 1)
                                            <div>
                                                <i data-feather="check-circle" class="text-success"
                                                    title="Index Request"></i>
                                                <span>
                                                    {{ \Carbon\Carbon::parse($page->index_date_time)->format('d M Y h:i A') }}
                                                </span>
                                            </div>
                                        @else
                                            <i data-feather="x-circle" class="text-danger" title="Unpublished"></i>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($page->is_published == 1)
                                            <div>
                                                <i data-feather="check-circle" class="text-success"
                                                    title="Published"></i>
                                                <span>
                                                    {{ \Carbon\Carbon::parse($page->published_date_time)->format('d M Y h:i A') }}
                                                </span>
                                            </div>
                                        @else
                                            <i data-feather="x-circle" class="text-danger" title="Unpublished"></i>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($page->published_date_time_by_job)
                                            <div>
                                                <span>
                                                    {{ \Carbon\Carbon::parse($page->published_date_time_by_job)->format('d M Y h:i A') }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="badge rounded-pill" style="color: red;">
                                                N/A
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($page->index_published_latest_date_time)
                                            <div>
                                                <span>
                                                    {{ \Carbon\Carbon::parse($page->index_published_latest_date_time)->format('d M Y h:i A') }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="badge rounded-pill" style="color: red;">
                                                N/A
                                            </span>
                                        @endif
                                    </td>

                                    <td>{{ @$page->createdBy->name }}</td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($page->created_at)->format('d M Y h:i A') }}
                                    </td>

                                    <td>{{ @$page->updatedBy->name }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($page->updated_at)->format('d M Y h:i A') }}
                                    </td>

                                    <td>
                                        <form method="POST" action="{{ route('pages.togglePublish', $page->id) }}"
                                            class="d-inline">
                                            @csrf
                                            <input type="checkbox" onchange="this.form.submit()"
                                                {{ $page->is_published == 1 ? 'checked' : '' }}
                                                title="{{ $page->is_published == 1 ? 'Unpublish this page' : 'Publish this page' }}">
                                        </form>


                                        <a href="{{ route('pages.edit', $page->id) }}">
                                            <i data-feather="edit-2" class="me-50"></i>
                                        </a>
                                        <form id="deleteForm-{{ $page->id }}" method="POST"
                                            action="{{ route('pages.delete', $page->id) }}" class="d-inline">
                                            @method('DELETE')
                                            @csrf
                                            <button type="button" class="btn-link"
                                                style="border: none; background: none; padding: 0; margin: 0;"
                                                onclick="confirmDelete(this.form)">
                                                <i data-feather="trash-2" class="me-50"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn-link"
                                            style="border: none; background: none; padding: 0; margin: 0;"
                                            onclick="copyPageUrl('{{ $page->page_url }}')">
                                            <i data-feather="copy" class="me-50"></i>
                                        </button>
                                        <a href="https://viserx.com/{{ $page->page_url }}" target="_blank">
                                            <i data-feather="external-link" class="me-50"></i>
                                        </a>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

                <div class="mx-1 d-flex justify-content-between">
                            <form method="GET" class="d-flex align-items-center" style="padding-top: 15px; padding-bottom: 15px;">
                                <label for="per_page" class="me-2">Show</label>
                                <select name="per_page" id="per_page" class="form-select form-select-sm w-auto me-2" onchange="this.form.submit()">
                                    @foreach ([50, 100, 200, 300, 500] as $limit)
                                        <option value="{{ $limit }}" {{ $limit == $perPage ? 'selected' : '' }}>{{ $limit }}</option>
                                    @endforeach
                                </select>
                                <span>entries</span>

                                @foreach(request()->except('per_page', 'page') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                            </form>
                            @if ($data->count() > 0 && $data->lastPage() > 1)
                                <nav aria-label="Page navigation">
                                    <ul class="pagination mt-2">
                                        <!-- Previous Button -->
                                        <li class="page-item prev">
                                            <a class="page-link"
                                            style="pointer-events: {{ $data->currentPage() == 1 ? 'none' : '' }}"
                                            href="{{ $data->appends(request()->except('page'))->url($data->currentPage() - 1) }}">
                                                <i class="feather-icon" data-feather="chevron-left"></i>
                                            </a>
                                        </li>

                                        <!-- First Page Button -->
                                        <li class="page-item {{ $data->currentPage() == 1 ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $data->appends(request()->except('page'))->url(1) }}">1</a>
                                        </li>

                                        <!-- Ellipsis if there are pages skipped -->
                                        @if ($data->currentPage() > 3)
                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                        @endif

                                        <!-- Page numbers near the current page -->
                                        @for ($i = max(2, $data->currentPage() - 2); $i <= min($data->lastPage() - 1, $data->currentPage() + 2); $i++)
                                            <li class="page-item {{ $i == $data->currentPage() ? 'active' : '' }}">
                                                <a class="page-link" href="{{ $data->appends(request()->except('page'))->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endfor

                                        <!-- Ellipsis if there are pages skipped -->
                                        @if ($data->currentPage() < $data->lastPage() - 2)
                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                        @endif

                                        <!-- Last Page Button -->
                                        <li class="page-item {{ $data->currentPage() == $data->lastPage() ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $data->appends(request()->except('page'))->url($data->lastPage()) }}">{{ $data->lastPage() }}</a>
                                        </li>

                                        <!-- Next Button -->
                                        <li class="page-item next">
                                            <a class="page-link"
                                            style="pointer-events: {{ $data->currentPage() == $data->lastPage() ? 'none' : '' }}"
                                            href="{{ $data->appends(request()->except('page'))->url($data->currentPage() + 1) }}">
                                                <i class="feather-icon" data-feather="chevron-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            @endif
                        </div>

            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="{{ route('pages.upload-country-city') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">Upload Country/City XLSX File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-info p-1">
                            <strong>Accepted Formats:</strong>
                            <ul class="mb-2">
                                <li>A column with only <b>Country</b></li>
                                <li>B columns with <b>Country</b> and <b>City</b></li>
                                <li>C columns with <b>Image URL</b></li>
                            </ul>
                            <div class="alert alert-warning p-2">
                                <strong>Note:</strong> <span class="text-dark">The <b>Country</b> column is mandatory. City
                                    is optional.</span>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-sm text-center mb-2">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Country</th>
                                            <th>City</th>
                                            <th>Banner Image Url</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>United States</td>
                                            <td>New York</td>
                                            <td>https://image.com/url</td>
                                        </tr>
                                        <tr>
                                            <td>Canada</td>
                                            <td>Toronto</td>
                                            <td>https://image.com/url</td>
                                        </tr>
                                        <tr>
                                            <td>Japan</td>
                                            <td></td>
                                            <td>https://image.com/url</td>
                                        </tr>
                                        <tr>
                                            <td>Germany</td>
                                            <td></td>
                                            <td>https://image.com/url</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mb-1">
                            <label for="ai_model" class="form-label">Select AI Model</label>
                            <select name="ai_model" id="ai_model" class="form-select" required>
                                <option value="" disabled selected>Select a model</option>
                                <option value="ChatGpt">ChatGPT</option>
                                <option value="DeepSeek">DeepSeek</option>
                            </select>
                        </div>
                        <input type="file" name="file" class="form-control mb-3" accept=".xlsx" required>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Upload</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Published Schedule Modal -->
    <div class="modal fade" id="publishedScheduleModal" tabindex="-1" aria-labelledby="publishedScheduleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="publishedScheduleForm" method="POST" action="{{ route('pages.bulkPublishSchedule') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="publishedScheduleModalLabel">Set Publish Schedule</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="page_ids" id="bulk_page_ids">

                        <div class="mb-3">
                            <label for="publish_date" class="form-label">Publish Date & Time</label>
                            <input type="datetime-local" class="form-control" name="publish_datetime"
                                id="publish_datetime" required value="{{$defaultDatetime}}" min="{{$defaultDatetime}}">
                        </div>

                        <div class="mb-3">
                            <label for="interval" class="form-label">Interval Duration</label>
                            <select class="form-select" name="interval" id="interval">
                                <option value="">None</option>

                                <!-- Minutes -->
                                <optgroup label="Minutes">
                                    <option value="5">5 Minutes</option>
                                    <option value="10">10 Minutes</option>
                                    <option value="20">20 Minutes</option>
                                    <option value="30">30 Minutes</option>
                                    <option value="40">40 Minutes</option>
                                    <option value="50">50 Minutes</option>
                                </optgroup>

                                <!-- Hours -->
                                <optgroup label="Hours">
                                    <option value="60">1 Hour</option>
                                    <option value="120">2 Hours</option>
                                    <option value="180">3 Hours</option>
                                    <option value="240">4 Hours</option>
                                    <option value="300">5 Hours</option>
                                    <option value="360">6 Hours</option>
                                    <option value="420">7 Hours</option>
                                    <option value="480">8 Hours</option>
                                    <option value="540">9 Hours</option>
                                    <option value="600">10 Hours</option>
                                    <option value="660">11 Hours</option>
                                </optgroup>

                                <!-- Days -->
                                <optgroup label="Days">
                                    <option value="1440">1 Day</option>
                                    <option value="2880">2 Days</option>
                                    <option value="4320">3 Days</option>
                                    <option value="5760">4 Days</option>
                                    <option value="7200">5 Days</option>
                                    <option value="8640">6 Days</option>
                                    <option value="10080">7 Days</option>
                                    <option value="11520">8 Days</option>
                                    <option value="12960">9 Days</option>
                                    <option value="14400">10 Days</option>
                                </optgroup>
                            </select>
                        </div>


                        <div id="selectedPagesInfo" class="small text-muted mt-2"></div>
                        <div id="lastScheduledInfo" class="small text-muted mt-1"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Schedule</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="scheduleGraphModal" tabindex="-1" aria-labelledby="scheduleGraphModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 90vw;">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title" id="scheduleGraphModalLabel">Scheduled Pages</h5>
                    <div class="d-flex align-items-center gap-2 mx-1">
                        <input type="date" id="filterStartDate" class="form-control form-control-sm" />
                        <input type="date" id="filterEndDate" class="form-control form-control-sm" />
                        <div class="d-flex gap-1">
                            <button id="filterGraphBtn" class="btn btn-sm btn-primary">Filter</button>
                            <button id="resetGraphBtn" class="btn btn-sm btn-secondary">Reset</button>
                            <button id="clearGraphCacheBtn" class="btn btn-sm btn-danger" style="width: 120px;">Clear Cache</button>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div id="chartWrapper" style="overflow-x: auto;">
                    <div class="modal-body">
                        <!-- Scrollable wrapper -->
                        <canvas id="scheduleGraphCanvas" height="700" style="display: block;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="indexGraphModal" tabindex="-1" aria-labelledby="indexGraphModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 90vw;">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title" id="indexGraphModalLabel">Last Activity Published/Indexed</h5>
                    <div class="d-flex align-items-center gap-2 mx-1">
                        <input type="date" id="indexFilterStartDate" class="form-control form-control-sm" />
                        <input type="date" id="indexFilterEndDate" class="form-control form-control-sm" />
                        <div class="d-flex gap-1">
                            <button id="indexFilterGraphBtn" class="btn btn-sm btn-primary">Filter</button>
                            <button id="indexResetGraphBtn" class="btn btn-sm btn-secondary">Reset</button>
                            <button id="indexClearCacheBtn" class="btn btn-sm btn-danger" style="width: 120px;">Clear Cache</button>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div id="indexChartWrapper" style="overflow-x: auto;">
                    <div class="modal-body">
                        <canvas id="indexGraphCanvas" height="700" style="display: block;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Hoverable rows end -->

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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
        function confirmDelete(form) {
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
                    form.submit();
                }
            });
        }
    </script>

    <script>
        // Function to copy the page URL to the clipboard
        function copyPageUrl(url) {
            // Create a temporary input element to copy the text
            const tempInput = document.createElement('input');
            document.body.appendChild(tempInput);
            tempInput.value = url;
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);

            // Optional: Notify the user that the URL has been copied
            alert('Page URL copied to clipboard!');
        }
    </script>

    <script>
        document.getElementById('select-all').addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('.page-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>

    <script>
        const selectAllCheckbox = document.getElementById('select-all');
        const pageCheckboxes = document.querySelectorAll('.page-checkbox');
        const bulkActionDropdown = document.getElementById('bulk-action-dropdown');

        function toggleBulkActionButton() {
            const anyChecked = Array.from(pageCheckboxes).some(cb => cb.checked);
            bulkActionDropdown.style.display = anyChecked ? 'block' : 'none';
        }

        selectAllCheckbox.addEventListener('change', function() {
            pageCheckboxes.forEach(cb => {
                cb.checked = selectAllCheckbox.checked;
            });
            toggleBulkActionButton();
        });

        pageCheckboxes.forEach(cb => {
            cb.addEventListener('change', toggleBulkActionButton);
        });

        function getSelectedPageIds() {
            return Array.from(pageCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
        }

        function bulkDelete() {
            const ids = getSelectedPageIds();

            if (ids.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Pages Selected',
                    text: 'Please select at least one page to delete.'
                });
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: 'Selected pages will be deleted permanently!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete them!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('pages.bulkDelete') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                ids: ids
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error("Network response was not ok");
                            }
                            return response.json(); // assuming server returns JSON
                        })
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Selected pages have been deleted.',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong while deleting!'
                            });
                            console.error('Bulk delete failed:', error);
                        });
                }
            });
        }

        function bulkSetIndex(indexValue) {
            const ids = getSelectedPageIds();
            if (ids.length === 0) {
                Swal.fire('No Pages Selected', 'Please select at least one page.', 'info');
                return;
            }

            Swal.fire({
                title: `Are you sure?`,
                text: `You are about to set selected pages as ${indexValue === 1 ? 'Index' : 'No Index'}.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('pages.bulkSetIndex') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                ids: ids,
                                index: indexValue
                            })
                        })
                        .then(res => res.json())
                        .then(response => {
                            Swal.fire({
                                icon: 'success',
                                title: `${indexValue === 1 ? 'Index' : 'No Index'}.`,
                                text: `Selected pages have been ${indexValue === 1 ? 'Index' : 'No Index'}.`,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        })
                        .catch(error => {
                            Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
                            console.error(error);
                        });
                }
            });
        }

        function bulkSetPublished(publishedValue) {
            const ids = getSelectedPageIds();
            if (ids.length === 0) {
                Swal.fire('No Pages Selected', 'Please select at least one page.', 'info');
                return;
            }

            Swal.fire({
                title: `Are you sure?`,
                text: `You are about to set selected pages as ${publishedValue === 1 ? 'Published' : 'Unpublished'}.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('pages.bulkSetPublished') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                ids: ids,
                                published: publishedValue
                            })
                        })
                        .then(res => res.json())
                        .then(response => {
                            Swal.fire({
                                icon: 'success',
                                title: `${publishedValue === 1 ? 'Published' : 'Unpublished'}.`,
                                text: `Selected pages have been ${publishedValue === 1 ? 'Published' : 'Unpublished'}.`,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        })
                        .catch(error => {
                            Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
                            console.error(error);
                        });
                }
            });
        }
    </script>

    <script>
        let selectedPageCount = 0;

        function bulkSetPublishedJob() {
            const selected = [...document.querySelectorAll('.page-checkbox:checked')].map(cb => cb.value);
            selectedPageCount = selected.length;

            if (selectedPageCount === 0) {
                alert('Please select at least one page.');
                return;
            }

            // const now = new Date();
            // const isoString = now.toISOString().slice(0, 16); // datetime-local format

            document.getElementById('bulk_page_ids').value = selected.join(',');
            // document.getElementById('publish_datetime').min = isoString;
            // document.getElementById('publish_datetime').value = isoString;

            document.getElementById('selectedPagesInfo').innerText = `You selected ${selectedPageCount} page(s)`;

            updateLastScheduledInfo();

            const modal = new bootstrap.Modal(document.getElementById('publishedScheduleModal'));
            modal.show();
        }

        function updateLastScheduledInfo() {
            const intervalValue = parseInt(document.getElementById('interval').value || '0');
            const baseTimeString = document.getElementById('publish_datetime').value;
            const baseTime = new Date(baseTimeString);

            let lastScheduledText = 'All pages will be published at the same time.';
            if (intervalValue > 0 && selectedPageCount > 1) {
                const lastTime = new Date(baseTime.getTime() + (selectedPageCount - 1) * intervalValue * 60000);
                const formatted = lastTime.toLocaleString(undefined, {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
                lastScheduledText = `Last scheduled publish time: ${formatted}`;
            }

            document.getElementById('lastScheduledInfo').innerText = lastScheduledText;
        }

        // Attach event listeners when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('interval').addEventListener('change', updateLastScheduledInfo);
            document.getElementById('publish_datetime').addEventListener('change', updateLastScheduledInfo);
        });
    </script>



    <script>

        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('scheduleGraphModal');

            function fetchAndRenderScheduleGraph(startDate = '', endDate = '') {
                const url = new URL("{{ route('schedule.graph.data') }}", window.location.origin);
                if (startDate) url.searchParams.append('start_date', startDate);
                if (endDate) url.searchParams.append('end_date', endDate);

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        const ctx = document.getElementById('scheduleGraphCanvas').getContext('2d');
                        if (window.scheduleChart) window.scheduleChart.destroy();

                        // Group jobs by date
                        const jobsByDate = {};
                        data.jobs.forEach(job => {
                            const date = job.time.split(' ')[0];
                            if (!jobsByDate[date]) jobsByDate[date] = [];
                            jobsByDate[date].push(job);
                        });

                        const rawLabels = Object.keys(jobsByDate);
                        const formattedLabels = rawLabels.map(formatDate);

                        const canvas = document.getElementById('scheduleGraphCanvas');
                        const barGroupWidth = 80;
                        canvas.width = rawLabels.length * barGroupWidth;

                        function generateBlueGradient(ctx, index, total) {
                            const hue = 220;
                            const saturation = 60 + (30 * index / total);
                            const lightnessStart = 30 + (30 * index / total);
                            const lightnessEnd = lightnessStart + 10;

                            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                            gradient.addColorStop(0, `hsl(${hue}, ${saturation}%, ${lightnessStart}%)`);
                            gradient.addColorStop(1, `hsl(${hue}, ${saturation}%, ${lightnessEnd}%)`);
                            return gradient;
                        }

                        const datasets = [];
                        let colorStep = 0;
                        const totalJobs = data.jobs.length;

                        rawLabels.forEach((date, i) => {
                            const jobs = jobsByDate[date];
                            jobs.forEach(job => {
                                const gradient = generateBlueGradient(ctx, colorStep,
                                totalJobs);
                                datasets.push({
                                    label: `${job.title} (${job.id})`,
                                    data: rawLabels.map(d => d === date ? 1 : 0),
                                    stack: 'stack1',
                                    backgroundColor: gradient,
                                    jobMeta: {
                                        title: job.title,
                                        timestamp: job.timestamp
                                    }
                                });
                                colorStep++;
                            });
                        });

                        const maxJobsPerDate = Math.max(...Object.values(jobsByDate).map(j => j.length));

                        const totalCountLabelPlugin = {
                            id: 'totalCountLabel',
                            afterDatasetsDraw(chart) {
                                const {
                                    ctx,
                                    scales: {
                                        x,
                                        y
                                    }
                                } = chart;
                                rawLabels.forEach((rawDate, index) => {
                                    const total = jobsByDate[rawDate].length;
                                    const xCenter = x.getPixelForValue(index);
                                    const yTop = y.getPixelForValue(total);
                                    ctx.save();
                                    ctx.font = 'bold 12px sans-serif';
                                    ctx.textAlign = 'center';
                                    ctx.fillStyle = '#000';
                                    ctx.fillText(`(${total})`, xCenter, yTop - 6);
                                    ctx.restore();
                                });
                            }
                        };

                        window.scheduleChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: formattedLabels,
                                datasets: datasets
                            },
                            options: {
                                responsive: false,
                                layout: {
                                    padding: {
                                        right: 20
                                    }
                                },
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            title: function(context) {
                                                return context[0].dataset.jobMeta.title;
                                            },
                                            label: function(context) {
                                                return context.dataset.jobMeta.timestamp;
                                            }
                                        }
                                    },
                                    title: {
                                        display: true,
                                        text: 'Scheduled Page Per Day (Detailed)'
                                    },
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    x: {
                                        stacked: true,
                                        title: {
                                            display: true,
                                            text: 'Scheduled Date'
                                        }
                                    },
                                    y: {
                                        stacked: true,
                                        beginAtZero: true,
                                        max: maxJobsPerDate + 5,
                                        title: {
                                            display: true,
                                            text: `Total Pages Count from ${formatDate(rawLabels[0])} to ${formatDate(rawLabels[rawLabels.length - 1])} (${totalJobs})`,
                                            font: {
                                                weight: 'bold', // makes the title bold
                                                size: 14     // optional: adjust font size
                                            }
                                        },
                                        ticks: {
                                            stepSize: 1
                                        }
                                    }
                                }
                            },

                            plugins: [totalCountLabelPlugin]
                        });
                    });
            }

            modal.addEventListener('shown.bs.modal', function() {
                fetchAndRenderScheduleGraph();
            });

            document.getElementById('filterGraphBtn').addEventListener('click', function() {
                const startDate = document.getElementById('filterStartDate').value;
                const endDate = document.getElementById('filterEndDate').value;
                fetchAndRenderScheduleGraph(startDate, endDate);
            });
            document.getElementById('resetGraphBtn').addEventListener('click', function () {
                document.getElementById('filterStartDate').value = '';
                document.getElementById('filterEndDate').value = '';
                fetchAndRenderScheduleGraph(); // reload full data
            });

            document.getElementById('clearGraphCacheBtn').addEventListener('click', clearGraphCache);
            function clearGraphCache() {
                Swal.fire({
                    title: `Clear Cache`,
                    text: `Are you sure you want to clear the schedule graph cache?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, proceed!',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("{{ route('pages.schedule-graph.clear-cache') }}", { // Make sure route name matches
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": '{{ csrf_token() }}'
                            },
                        })
                        .then(res => res.json())
                        .then(response => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Clear Cache',
                                text: `Cache cleared successfully`,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            fetchAndRenderScheduleGraph();
                        })
                        .catch(error => {
                            Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
                            console.error(error);
                        });
                    }
                });
            }
        });

        function formatDate(dateStr) {
            const date = new Date(dateStr);
            return new Intl.DateTimeFormat('en-US', {
                month: 'short',
                day: '2-digit',
                year: 'numeric'
            }).format(date);
        }
    </script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const indexModal = document.getElementById('indexGraphModal');

        function fetchAndRenderIndexGraph(startDate = '', endDate = '') {
            const url = new URL("{{ route('index.graph.data') }}", window.location.origin);
            if (startDate) url.searchParams.append('start_date', startDate);
            if (endDate) url.searchParams.append('end_date', endDate);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('indexGraphCanvas').getContext('2d');
                    if (window.indexChart) window.indexChart.destroy();

                    const jobsByDate = {};
                    data.jobs.forEach(job => {
                        const date = new Date(job.time).toISOString().split('T')[0];
                        if (!jobsByDate[date]) jobsByDate[date] = [];
                        jobsByDate[date].push(job);
                    });

                    const rawLabels = Object.keys(jobsByDate);
                    const formattedLabels = rawLabels.map(formatDate);

                    const canvas = document.getElementById('indexGraphCanvas');
                    const barGroupWidth = 80;
                    canvas.width = rawLabels.length * barGroupWidth;

                    function generateBlueGradient(ctx, index, total) {
                        const hue = 220;
                        const saturation = 60 + (30 * index / total);
                        const lightnessStart = 30 + (30 * index / total);
                        const lightnessEnd = lightnessStart + 10;

                        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                        gradient.addColorStop(0, `hsl(${hue}, ${saturation}%, ${lightnessStart}%)`);
                        gradient.addColorStop(1, `hsl(${hue}, ${saturation}%, ${lightnessEnd}%)`);
                        return gradient;
                    }

                    const datasets = [];
                    let colorStep = 0;
                    const totalJobs = data.jobs.length;

                    rawLabels.forEach((date, i) => {
                        const jobs = jobsByDate[date];
                        jobs.forEach(job => {
                            const gradient = generateBlueGradient(ctx, colorStep, totalJobs);
                            datasets.push({
                                label: `${job.title} (${job.id})`,
                                data: rawLabels.map(d => d === date ? 1 : 0),
                                stack: 'stack1',
                                backgroundColor: gradient,
                                jobMeta: {
                                    id: job.id,
                                    title: job.title,
                                    timestamp: job.timestamp
                                }
                            });
                            colorStep++;
                        });
                    });

                    const maxJobsPerDate = Math.max(...Object.values(jobsByDate).map(j => j.length));

                    const totalCountLabelPlugin = {
                        id: 'totalCountLabel',
                        afterDatasetsDraw(chart) {
                            const { ctx, scales: { x, y } } = chart;
                            rawLabels.forEach((rawDate, index) => {
                                const total = jobsByDate[rawDate].length;
                                const xCenter = x.getPixelForValue(index);
                                const yTop = y.getPixelForValue(total);
                                ctx.save();
                                ctx.font = 'bold 12px sans-serif';
                                ctx.textAlign = 'center';
                                ctx.fillStyle = '#000';
                                ctx.fillText(`(${total})`, xCenter, yTop - 6);
                                ctx.restore();
                            });
                        }
                    };

                    // New plugin to handle x-axis label tooltips and clicks
                    const xAxisLabelTooltipPlugin = {
                        id: 'xAxisLabelTooltip',
                        afterEvent(chart, args) {
                            const {ctx, chartArea, scales: { x, y }, canvas} = chart;
                            const event = args.event;

                            if (event.type === 'mousemove' || event.type === 'mouseout') {
                                const mouseX = event.x;
                                const mouseY = event.y;

                                // Clear previous tooltip if any
                                chart.draw();

                                if (event.type === 'mousemove') {
                                    let hoveredIndex = null;

                                    rawLabels.forEach((label, index) => {
                                        const labelX = x.getPixelForValue(index);
                                        const labelY = chartArea.bottom + 10;

                                        // Check if mouse is near label (within 15 px horizontally and vertically)
                                        if (mouseX >= labelX - 15 && mouseX <= labelX + 15 && mouseY >= labelY - 20 && mouseY <= labelY + 20) {
                                            hoveredIndex = index;
                                        }
                                    });

                                    if (hoveredIndex !== null) {
                                        canvas.style.cursor = 'pointer';

                                        const labelX = x.getPixelForValue(hoveredIndex);
                                        const labelY = chartArea.bottom + 20;

                                        // Draw tooltip box
                                        const tooltipText = `Search for ${rawLabels[hoveredIndex]}`;
                                        const padding = 6;
                                        ctx.save();
                                        ctx.font = '12px sans-serif';

                                        const textWidth = ctx.measureText(tooltipText).width;
                                        const boxWidth = textWidth + padding * 2;
                                        const boxHeight = 24;

                                        ctx.fillStyle = 'rgba(0, 0, 0, 0.7)';
                                        ctx.roundRect(labelX - boxWidth / 2, labelY, boxWidth, boxHeight, 5);
                                        ctx.fill();

                                        ctx.fillStyle = '#fff';
                                        ctx.textAlign = 'center';
                                        ctx.textBaseline = 'middle';
                                        ctx.fillText(tooltipText, labelX, labelY + boxHeight / 2);

                                        ctx.restore();
                                    } else {
                                        canvas.style.cursor = 'default';
                                    }
                                } else if (event.type === 'mouseout') {
                                    canvas.style.cursor = 'default';
                                }
                            }

                            if (event.type === 'click') {
                                const mouseX = event.x;
                                const mouseY = event.y;

                                rawLabels.forEach((label, index) => {
                                    const labelX = x.getPixelForValue(index);
                                    const labelY = chartArea.bottom + 10;

                                    if (mouseX >= labelX - 15 && mouseX <= labelX + 15 && mouseY >= labelY - 20 && mouseY <= labelY + 20) {
                                        // Redirect to your search page URL, e.g.:
                                        const searchUrl = `{{ route('ai-seo-pages') }}?last_published_index_date=${label}`; // Replace with your real route
                                        window.open(searchUrl, '_blank');
                                    }
                                });
                            }
                        }
                    };

                    window.indexChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: formattedLabels,
                            datasets: datasets
                        },
                        options: {
                            responsive: false,
                            layout: {
                                padding: { right: 20 }
                            },
                            onClick: function (evt, elements) {
                                if (elements.length > 0) {
                                    const datasetIndex = elements[0].datasetIndex;
                                    const dataset = this.data.datasets[datasetIndex];
                                    if (dataset && dataset.jobMeta && dataset.jobMeta.id) {
                                        const editUrl = `{{ route('pages.edit', ':id') }}`.replace(':id', dataset.jobMeta.id);
                                        window.open(editUrl, '_blank');
                                    }
                                }
                            },
                            onHover: function (event, chartElement) {
                                if (chartElement.length) {
                                    event.native.target.style.cursor = 'pointer';
                                } else {
                                    event.native.target.style.cursor = 'default';
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        title: function (context) {
                                            return context[0].dataset.jobMeta.title;
                                        },
                                        label: function (context) {
                                            return context.dataset.jobMeta.timestamp;
                                        }
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Last Activity Published/Indexed Per Day (Detailed)'
                                },
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                x: {
                                    stacked: true,
                                    title: {
                                        display: true,
                                        text: 'Last Activity Published/Indexed'
                                    }
                                },
                                y: {
                                    stacked: true,
                                    beginAtZero: true,
                                    max: maxJobsPerDate + 5,
                                    title: {
                                        display: true,
                                        text: `Total Last Activity Published/Indexed from ${formatDate(rawLabels[0])} to ${formatDate(rawLabels[rawLabels.length - 1])} (${totalJobs})`,
                                        font: {
                                            weight: 'bold',
                                            size: 14
                                        }
                                    },
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        },
                        plugins: [totalCountLabelPlugin, xAxisLabelTooltipPlugin]
                    });
                });
        }

        indexModal.addEventListener('shown.bs.modal', function () {
            fetchAndRenderIndexGraph();
        });

        document.getElementById('indexFilterGraphBtn').addEventListener('click', function () {
            const startDate = document.getElementById('indexFilterStartDate').value;
            const endDate = document.getElementById('indexFilterEndDate').value;
            fetchAndRenderIndexGraph(startDate, endDate);
        });

        document.getElementById('indexResetGraphBtn').addEventListener('click', function () {
            document.getElementById('indexFilterStartDate').value = '';
            document.getElementById('indexFilterEndDate').value = '';
            fetchAndRenderIndexGraph();
        });

        document.getElementById('indexClearCacheBtn').addEventListener('click', clearIndexGraphCache);
        function clearIndexGraphCache() {
            Swal.fire({
                title: `Clear Cache`,
                text: `Are you sure you want to clear the index graph cache?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('pages.index-graph.clear-cache') }}", { // Make sure route name matches
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": '{{ csrf_token() }}'
                        },
                    })
                    .then(res => res.json())
                    .then(response => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Clear Cache',
                            text: `Cache cleared successfully`,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        fetchAndRenderIndexGraph();
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
                        console.error(error);
                    });
                }
            });
        }

        // Add roundRect polyfill for canvas for older browsers (if not available)
        if (!CanvasRenderingContext2D.prototype.roundRect) {
            CanvasRenderingContext2D.prototype.roundRect = function (x, y, w, h, r) {
                if (w < 2 * r) r = w / 2;
                if (h < 2 * r) r = h / 2;
                this.beginPath();
                this.moveTo(x + r, y);
                this.arcTo(x + w, y, x + w, y + h, r);
                this.arcTo(x + w, y + h, x, y + h, r);
                this.arcTo(x, y + h, x, y, r);
                this.arcTo(x, y, x + w, y, r);
                this.closePath();
                return this;
            }
        }
    });
</script>




@endsection
