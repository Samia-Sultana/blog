@extends('layouts/contentLayoutMaster')

@section('title', 'Admission List')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('content')

<style>
    .placeholder-label {
        font-weight: bold;
        font-size: 0.9rem;
        color: #6c757d;
    }
    .custom_button {
        padding: 10px 44px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .section-divider {
        border-top: 2px dashed #dcdcdc;
        margin: 2rem 0;
    }
    .badge-soft {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.12);
        font-weight: 600;
        padding: 0.5em 1em;
        font-size: 0.9rem;
    }
    .card-modern {
        border-radius: 1rem;
        background: linear-gradient(to bottom right, #ffffff, #f8f9fa);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    }
    .btn-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
</style>

@if(Session::has('success'))
    <div id="success-alert" class="alert alert-success" style="padding: 15px;">
        {{ Session::get('success') }}
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card card-modern">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Application Details</h4>
                <div class="d-flex">
                    <a href="{{ route('job-application') }}" class="btn btn-outline-secondary me-1">
                        <i data-feather="arrow-left"></i> Back
                    </a>
                </div>
            </div>

            <div class="card-body">
                {{-- Basic Information --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label class="placeholder-label">Application ID:</label>
                            <p class="h6">{{ $application->id }}</p>
                        </div>
                        <div class="mb-2">
                            <label class="placeholder-label">Name:</label>
                            <p class="h5">{{ $application->name }}</p>
                        </div>
                        <div class="mb-2">
                            <label class="placeholder-label">Email:</label>
                            <p class="h6">{{ $application->email }}</p>
                        </div>
                        <div class="mb-2">
                            <label class="placeholder-label">Phone:</label>
                            <p class="h6">{{ $application->contact }}</p>
                        </div>
                        <div class="mb-2">
                            <label class="placeholder-label">Applying For:</label>
                            <p class="h6">
                                @if ($application->applying_position)
                                    <span>{{ $application->applying_position }}</span>
                                @else
                                    <span class="badge rounded-pill" style="color: red">
                                    N/A
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-2">
                            <label class="placeholder-label">Department:</label>
                            <p>
                                @if($application->deparmentLabel)
                                    <span class="badge rounded-pill badge-soft"
                                          style="background-color: {{ $application->deparmentLabel->background_color }};
                                                 color: {{ $application->deparmentLabel->text_color }};">
                                        {{ $application->deparmentLabel->name }}
                                    </span>
                                @else
                                    <span class="badge rounded-pill" style="color: red">
                                        N/A
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="mb-2">
                            <label class="placeholder-label">Position:</label>
                            <p>
                                @if($application->positionLabel)
                                    <span class="badge rounded-pill badge-soft"
                                          style="background-color: {{ $application->positionLabel->background_color }};
                                                 color: {{ $application->positionLabel->text_color }};">
                                        {{ $application->positionLabel->name }}
                                    </span>
                                @else
                                <span class="badge rounded-pill" style="color: red">
                                    N/A
                                </span>
                                @endif
                            </p>
                        </div>
                        <div class="mb-2">
                            <label class="placeholder-label">Status:</label>
                            <p>
                                @if($application->statusLabel)
                                    <span class="badge rounded-pill badge-soft"
                                          style="background-color: {{ $application->statusLabel->background_color }};
                                                 color: {{ $application->statusLabel->text_color }};">
                                        {{ $application->statusLabel->name }}
                                    </span>
                                @else
                                    <span class="badge rounded-pill" style="color: red">
                                    N/A
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="mb-2">
                            <label class="placeholder-label">Applied Date:</label>
                            <p class="h6">{{ $application->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                        <div class="mb-2">
                            <label class="placeholder-label">Application Type:</label>
                            <p class="h6">
                                @if ($application->application_type)
                                    {{ \App\Enums\ApplicationTypeEnum::getString(\App\Enums\ApplicationTypeEnum::from($application->application_type)) }}
                                @else
                                    <span class="badge rounded-pill" style="color: red">
                                    N/A
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Divider --}}
                <hr class="section-divider">

                {{-- Update Status --}}
                <div class="row mt-3">
                    <div class="col-12">
                        <h5>Update Status</h5>
                        <form method="POST" action="{{ route('update.application.status', $application->id) }}">
                            @csrf
                            <div class="row align-items-end">
                                <div class="col-md-6">
                                    <div class="">
                                        <label class="form-label">Select New Status</label>
                                        <select class="form-select select2" name="status_id" required>
                                            <option value="">-- Select Status --</option>
                                            @foreach($statuses as $status)
                                                <option value="{{ $status->id }}" {{ $application->status_id == $status->id ? 'selected' : '' }}>
                                                    {{ $status->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary custom_button">
                                        Update Status
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Divider --}}
                <hr class="section-divider">

                {{-- Resume --}}
                @if($application->cv_file)
                    <div class="row mt-2">
                        <div class="col-12">
                            <label class="placeholder-label">Resume:</label>
                            <div class="d-flex gap-2 mt-1">
                                <a href="{{ asset($application->cv_file) }}"
                                   target="_blank"
                                   class="btn btn-outline-primary btn-icon"
                                   data-bs-toggle="tooltip"
                                   data-bs-placement="top"
                                   title="View Resume">
                                    <i data-feather="eye"></i>
                                </a>
                                <a href="{{ asset($application->cv_file) }}"
                                   download
                                   class="btn btn-primary btn-icon"
                                   data-bs-toggle="tooltip"
                                   data-bs-placement="top"
                                   title="Download Resume">
                                    <i data-feather="download"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

            </div> <!-- End card-body -->
        </div> <!-- End card -->
    </div>
</div>

@endsection

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();

            // Feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Bootstrap tooltip
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
@endsection
