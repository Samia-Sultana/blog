@extends('layouts/contentLayoutMaster')

@section('title', 'Company Deck')

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
                <form method="GET" action="{{ route('company-deck') }}">
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
                                <label class="form-label" for="contact_number">Phone</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{ request('contact_number') }}" placeholder="Enter phone number">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="industry">All Industry</label>
                                <select class="select2 form-select" id="industry" name="industry">
                                    <option value="">All Industry</option>
                                    <option value="Technology" {{ request('industry') == 'Technology' ? 'selected' : '' }}>Technology</option>
                                    <option value="E-commerce" {{ request('industry') == 'E-commerce' ? 'selected' : '' }}>E-commerce</option>
                                    <option value="Finance and Banking" {{ request('industry') == 'Finance and Banking' ? 'selected' : '' }}>Finance and Banking</option>
                                    <option value="Education" {{ request('industry') == 'Education' ? 'selected' : '' }}>Education</option>
                                    <option value="Entertainment and Media" {{ request('industry') == 'Entertainment and Media' ? 'selected' : '' }}>Entertainment and Media</option>
                                    <option value="Retail" {{ request('industry') == 'Retail' ? 'selected' : '' }}>Retail</option>
                                    <option value="Entertainment" {{ request('industry') == 'Entertainment' ? 'selected' : '' }}>Entertainment</option>
                                    <option value="Food Industry" {{ request('industry') == 'Food Industry' ? 'selected' : '' }}>Food Industry</option>
                                    <option value="Law" {{ request('industry') == 'Law' ? 'selected' : '' }}>Law</option>
                                    <option value="Real Estate" {{ request('industry') == 'Real Estate' ? 'selected' : '' }}>Real Estate</option>
                                    <option value="Exercise" {{ request('industry') == 'Exercise' ? 'selected' : '' }}>Exercise</option>
                                    <option value="Car Dealership" {{ request('industry') == 'Car Dealership' ? 'selected' : '' }}>Car Dealership</option>
                                    <option value="Healthcare Industry" {{ request('industry') == 'Healthcare Industry' ? 'selected' : '' }}>Healthcare Industry</option>
                                    <option value="Fashion" {{ request('industry') == 'Fashion' ? 'selected' : '' }}>Fashion</option>
                                    <option value="Restaurant" {{ request('industry') == 'Restaurant' ? 'selected' : '' }}>Restaurant</option>
                                    <option value="Analytics" {{ request('industry') == 'Analytics' ? 'selected' : '' }}>Analytics</option>
                                    <option value="Design" {{ request('industry') == 'Design' ? 'selected' : '' }}>Design</option>
                                    <option value="Hospitality Industry" {{ request('industry') == 'Hospitality Industry' ? 'selected' : '' }}>Hospitality Industry</option>
                                    <option value="IT" {{ request('industry') == 'IT' ? 'selected' : '' }}>IT</option>
                                    <option value="Professional Services" {{ request('industry') == 'Professional Services' ? 'selected' : '' }}>Professional Services</option>
                                    <option value="Social Media" {{ request('industry') == 'Social Media' ? 'selected' : '' }}>Social Media</option>
                                    <option value="Others" {{ request('industry') == 'Others' ? 'selected' : '' }}>Others</option>
                                </select>
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
                            <div class="col-md-4 mb-1 d-flex align-items-center" style="margin-top: 25px;">
                                <button type="submit" class="btn btn-primary waves-effect waves-float waves-light">
                                    Filter
                                </button>
                                @if(request()->hasAny(['name', 'email', 'contact_number', 'from_date', 'to_date', 'industry']))
                                    <a href="{{ url()->current() }}" class="mx-1 btn btn-outline-secondary">Reset</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
                <div class="d-flex justify-content-end" style="margin-top: -50px;">
                    <form method="POST" action="{{ route('company-deck.export') }}">
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
                            <th>Id</th>
                            <th>name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Industry</th>
                            <th>Budget</th>
                            <th>Website</th>
                            <th>
                                <a href="{{ route('company-deck', array_merge(request()->except('page'), [
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
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->contact_number }}</td>
                                <td>{{ $item->industry }}</td>
                                <td>USD {{ $item->month_marketing_budget }}</td>
                                <td>{{ $item->website_url }}</td>
                                <td>{{ $item->created_at->format('M d, Y h:i A') }}</td>
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
