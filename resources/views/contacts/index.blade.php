@extends('layouts/contentLayoutMaster')

@section('title', 'Contacts')

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
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Filters</h4>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('contacts') }}">
                    <div class="filter-section">
                        <div class="row filter-row">
                            <div class="col-md-3">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ request('name') }}" placeholder="Enter Name">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="email">Email</label>
                                <input type="text" class="form-control" id="email" name="email" value="{{ request('email') }}" placeholder="Enter E-mail">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="phone">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ request('phone') }}" placeholder="Enter phone number">
                            </div>

                        </div>
                        <div class="row filter-row">
                            <div class="col-md-2">
                                <label class="form-label" for="from_date">From Date</label>
                                <input type="date" class="form-control flatpickr-basic" id="from_date" name="from_date" value="{{ request('from_date') }}" placeholder="YYYY-MM-DD">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="to_date">To Date</label>
                                <input type="date" class="form-control flatpickr-basic" id="to_date" name="to_date" value="{{ request('to_date') }}" placeholder="YYYY-MM-DD">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="status">All Status</label>
                                <select class="select2 form-select" id="status" name="status">
                                    <option value="">All Status</option>
                                    <option value="{{ \App\Enums\ContactStatusEnum::Unread->value }}" {{ request('status') == \App\Enums\ContactStatusEnum::Unread->value ? 'selected' : '' }}>
                                        {{\App\Enums\ContactStatusEnum::Unread->value}}
                                    </option>
                                    <option value="{{ \App\Enums\ContactStatusEnum::Read->value }}" {{ request('status') == \App\Enums\ContactStatusEnum::Read->value ? 'selected' : '' }}>
                                        {{\App\Enums\ContactStatusEnum::Read->value}}
                                    </option>
                                    <option value="{{ \App\Enums\ContactStatusEnum::FollowUp->value }}" {{ request('status') == \App\Enums\ContactStatusEnum::FollowUp->value ? 'selected' : '' }}>
                                        {{\App\Enums\ContactStatusEnum::FollowUp->value}}
                                    </option>
                                    <option value="{{ \App\Enums\ContactStatusEnum::Ignored->value }}" {{ request('status') == \App\Enums\ContactStatusEnum::Ignored->value ? 'selected' : '' }}>
                                        {{\App\Enums\ContactStatusEnum::Ignored->value}}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-1 d-flex align-items-center" style="margin-top: 25px;">
                                <button type="submit" class="btn btn-primary waves-effect waves-float waves-light">
                                    Filter
                                </button>
                                @if(request()->hasAny(['name', 'email', 'phone', 'from_date', 'to_date', 'status']))
                                    <a href="{{ url()->current() }}" class="mx-1 btn btn-outline-secondary">Reset</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
                <div class="d-flex justify-content-end" style="margin-top: -50px;">
                    <form method="POST" action="{{ route('contacts.export') }}">
                        @csrf
                        {{-- Preserve filter inputs --}}
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
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>subject</th>
                            <th>Status</th>
                            <th>
                                <a href="{{ route('contacts', array_merge(request()->except('page'), [
                                    'sort_by' => 'created_at',
                                    'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'created_at' ? 'desc' : 'asc'
                                ])) }}" class="text-decoration-none text-dark">
                                    Created At
                                    @php
                                        $sortBy = request('sort_by', 'created_at');
                                        $sortOrder = request('sort_order', 'desc');
                                    @endphp
                                    @if($sortBy === 'created_at')
                                        {!! $sortOrder === 'asc' ? '▲' : '▼' !!}
                                    @endif
                                </a>
                            </th>


                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->phone }}</td>
                                <td>{{ $item->subject }}</td>
                                <td>
                                    <span class="badge rounded-pill  {{ App\Enums\ContactStatusEnum::from($item->status)->getColorClass() }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td>{{ $item->created_at->format('M d, Y h:i A') }}</td>
                                <td>
                                    <a href="{{ route('contacts.show', $item->id) }}" class="btn btn-sm btn-link text-primary" title="View Details">
                                        <i data-feather="eye" class="me-50"></i> <!-- Font Awesome eye icon -->
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mx-1 d-flex justify-content-between">
                        <form method="GET" class="d-flex align-items-center">
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
                        <nav aria-label="Page navigation">
                            <ul class="pagination mt-2">
                                <li class="page-item prev"><a class="page-link"
                                                              style="pointer-events: {{ $data->currentPage() == 1 ? 'none' : '' }}"
                                                              href="{{ $data->url($data->currentPage() - 1) }}"></a>
                                </li>
                                @for ($i = 1; $i <= $data->lastPage(); $i++)
                                    <li class="page-item {{ $i == $data->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="page-item next" disabled><a class="page-link"
                                                                       style="pointer-events: {{ $data->currentPage() == $data->lastPage() ? 'none' : '' }}"
                                                                       href="{{ $data->url($data->currentPage() + 1) }}"></a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection


@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection
@section('page-script')
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
@endsection
