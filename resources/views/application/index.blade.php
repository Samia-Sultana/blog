@extends('layouts/contentLayoutMaster')

@section('title', 'Application List')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('content')

    <style>
        .placeholder-label{
            font-weight: bold;
        }

        .custom_button{
            padding: 10px 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>

@if(Session::has('success'))
    <div id="success-alert" class="alert alert-success" style="padding: 15px;">
        {{ Session::get('success') }}
    </div>
@endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Application Filters</h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('job-application') }}">
                        <div class="filter-section">
                            <div class="row filter-row">
                                <div class="col-md-2">
                                    <label class="form-label" for="position_label">Position</label>
                                    <select class="select2 form-select" id="position_label" name="position_label">
                                        <option value="">All Positions</option>
                                        @foreach($positionLabels as $label)
                                            <option value="{{ $label->id }}" {{ request('position_label') == $label->id ? 'selected' : '' }}>
                                                {{ $label->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" for="status_label">Status</label>
                                    <select class="select2 form-select" id="status_label" name="status_label">
                                        <option value="">All Statuses</option>
                                        @foreach($statusLabels as $label)
                                            <option value="{{ $label->id }}" {{ request('status_label') == $label->id ? 'selected' : '' }}>
                                                {{ $label->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" for="status_label">Applicant Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ request('name') }}" placeholder="Enter Name">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" for="status_label">Applicant Email</label>
                                    <input type="text" class="form-control" id="email" name="email" value="{{ request('email') }}" placeholder="Enter E-mail">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" for="from_date">From Date</label>
                                    <input type="date" class="form-control flatpickr-basic" id="from_date" name="from_date" value="{{ request('from_date') }}" placeholder="YYYY-MM-DD">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" for="to_date">To Date</label>
                                    <input type="date" class="form-control flatpickr-basic" id="to_date" name="to_date" value="{{ request('to_date') }}" placeholder="YYYY-MM-DD">
                                </div>
                            </div>
                            <div class="row filter-row">
                                <div class="col-md-2">
                                    <label class="form-label" for="department_label">Department</label>
                                    <select class="select2 form-select" id="department_label" name="department_label">
                                        <option value="">All Department</option>
                                        @foreach($departmentLabels as $label)
                                            <option value="{{ $label->id }}" {{ request('department_label') == $label->id ? 'selected' : '' }}>
                                                {{ $label->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" for="application_type">Application Type</label>
                                    <select class="select2 form-select" id="application_type" name="application_type">
                                        <option value="">All Application</option>
                                        <option value="{{ \App\Enums\ApplicationTypeEnum::IN_SIDE->value }}" {{ request('application_type') == \App\Enums\ApplicationTypeEnum::IN_SIDE->value ? 'selected' : '' }}>
                                            {{ \App\Enums\ApplicationTypeEnum::getString(\App\Enums\ApplicationTypeEnum::from(\App\Enums\ApplicationTypeEnum::IN_SIDE->value))}}
                                        </option>
                                        <option value="{{ \App\Enums\ApplicationTypeEnum::OUT_SIDE->value }}" {{ request('application_type') == \App\Enums\ApplicationTypeEnum::OUT_SIDE->value ? 'selected' : '' }}>
                                            {{ \App\Enums\ApplicationTypeEnum::getString(\App\Enums\ApplicationTypeEnum::from(\App\Enums\ApplicationTypeEnum::OUT_SIDE->value))}}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" for="phone">Applicant Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ request('phone') }}" placeholder="Enter phone number">
                                </div>

                                <div class="col-md-2 mb-1 d-flex align-items-center" style="margin-top: 25px;">
                                    <button type="submit" class="btn btn-primary waves-effect waves-float waves-light">
                                        Filter
                                    </button>
                                    @if(request()->hasAny(['position_label', 'status_label', 'department_label', 'name', 'email', 'from_date', 'to_date', 'phone' ,'application_type']))
                                    <a href="{{ url()->current() }}" class="mx-1 btn btn-outline-secondary">Reset</a>
                                @endif
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="d-flex justify-content-end" style="margin-top: -50px;">
                        <form method="POST" action="{{ route('job.export') }}">
                            @csrf
                            @foreach(request()->all() as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach

                            <button type="submit" class="btn btn-primary waves-effect waves-float waves-light">
                                Export
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="table-hover-row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Applications List</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Position</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                    <th>Application Type</th>
                                    <th>
                                        <a href="{{ route('job-application', array_merge(request()->except('page'), [
                                            'sort_by' => 'created_at',
                                            'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'created_at' ? 'desc' : 'asc'
                                        ])) }}" class="text-decoration-none text-dark">
                                            Applied Date
                                            @php
                                                $sortBy = request('sort_by', 'created_at');
                                                $sortOrder = request('sort_order', 'desc');
                                            @endphp
                                            @if($sortBy === 'created_at')
                                                {!! $sortOrder === 'asc' ? '▲' : '▼' !!}
                                            @endif
                                        </a>
                                    </th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applications as $application)
                                    <tr>
                                        <td>{{ ($applications->currentPage() - 1) * $applications->perPage() + $loop->iteration }}</td>
                                        <td>{{ $application->name }}</td>
                                        <td>{{ $application->email }}</td>
                                        <td>{{ $application->contact }}</td>
                                        <td>
                                            @if($application->positionLabel)
                                                <span class="badge rounded-pill"
                                                style="background-color: {{ $application->positionLabel->background_color }};
                                                        color: {{ $application->positionLabel->text_color }};
                                                        border-radius: 10px;
                                                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);"
                                                >
                                                    {{ $application->positionLabel->name }}
                                                </span>
                                            @else
                                                <span class="badge rounded-pill" style="color: red">
                                                N/A
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($application->deparmentLabel)
                                                <span class="badge rounded-pill"
                                                style="background-color: {{ $application->deparmentLabel->background_color }};
                                                        color: {{ $application->deparmentLabel->text_color }};
                                                        border-radius: 10px;
                                                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);"
                                                >
                                                    {{ $application->deparmentLabel->name }}
                                                </span>
                                            @else
                                                <span class="badge rounded-pill" style="color: red">
                                                N/A
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($application->statusLabel)
                                                <span class="badge rounded-pill"
                                                        style="background-color: {{ $application->statusLabel->background_color }};
                                                        color: {{ $application->statusLabel->text_color }};
                                                        border-radius: 10px;
                                                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);"
                                                >
                                                    {{ $application->statusLabel->name }}
                                                </span>
                                            @else
                                                <span class="badge rounded-pill" style="color: red">
                                                N/A
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $application->application_type
                                                ? \App\Enums\ApplicationTypeEnum::getString(\App\Enums\ApplicationTypeEnum::from($application->application_type))
                                                : 'N/A' }}
                                        </td>


                                        <td>{{ $application->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                                    <i data-feather="more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{ route('view.application', $application->id) }}">
                                                        <i data-feather="eye"></i>
                                                        <span>View</span>
                                                    </a>
                                                </div>
                                            </div>
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
                                @foreach ([10, 20, 50, 100, 500] as $limit)
                                    <option value="{{ $limit }}" {{ $limit == $perPage ? 'selected' : '' }}>{{ $limit }}</option>
                                @endforeach
                            </select>
                            <span>entries</span>

                            @foreach(request()->except('per_page', 'page') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                        </form>
                        @if ($applications->count() > 0 && $applications->lastPage() > 1)
                            <nav aria-label="Page navigation">
                                <ul class="pagination mt-2">
                                    <!-- Previous Button -->
                                    <li class="page-item prev">
                                        <a class="page-link"
                                        style="pointer-events: {{ $applications->currentPage() == 1 ? 'none' : '' }}"
                                        href="{{ $applications->appends(request()->except('page'))->url($applications->currentPage() - 1) }}">
                                            <i class="feather-icon" data-feather="chevron-left"></i>
                                        </a>
                                    </li>

                                    <!-- First Page Button -->
                                    <li class="page-item {{ $applications->currentPage() == 1 ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $applications->appends(request()->except('page'))->url(1) }}">1</a>
                                    </li>

                                    <!-- Ellipsis if there are pages skipped -->
                                    @if ($applications->currentPage() > 3)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif

                                    <!-- Page numbers near the current page -->
                                    @for ($i = max(2, $applications->currentPage() - 2); $i <= min($applications->lastPage() - 1, $applications->currentPage() + 2); $i++)
                                        <li class="page-item {{ $i == $applications->currentPage() ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $applications->appends(request()->except('page'))->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor

                                    <!-- Ellipsis if there are pages skipped -->
                                    @if ($applications->currentPage() < $applications->lastPage() - 2)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif

                                    <!-- Last Page Button -->
                                    <li class="page-item {{ $applications->currentPage() == $applications->lastPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $applications->appends(request()->except('page'))->url($applications->lastPage()) }}">{{ $applications->lastPage() }}</a>
                                    </li>

                                    <!-- Next Button -->
                                    <li class="page-item next">
                                        <a class="page-link"
                                        style="pointer-events: {{ $applications->currentPage() == $applications->lastPage() ? 'none' : '' }}"
                                        href="{{ $applications->appends(request()->except('page'))->url($applications->currentPage() + 1) }}">
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
    </div>

    <!-- Hoverable rows end -->

@endsection

@section('vendor-script')
    <!-- vendor js files -->
    <script src="{{ asset(mix('vendors/js/pagination/jquery.bootpag.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pagination/jquery.twbsPagination.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection
@section('page-script')
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
@endsection
