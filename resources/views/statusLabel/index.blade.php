@extends('layouts/contentLayoutMaster')

@section('title', 'Status Label List')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('content')

@if(Session::has('success'))
    <div id="success-alert" class="alert alert-success" style="padding: 15px;">
        {{ Session::get('success') }}
    </div>
@endif

    <div class="row" id="table-hover-row">
        <div class="col-12">
            <div class="card">

                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-end">
                        <form method="GET" action="{{ route('status-label') }}" class="w-50 w-md-auto me-md-3">
                            <div class="row g-2 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold" for="type">Type</label>
                                    <select class="select2 form-select" id="type" name="type">
                                        <option value="">All Type</option>
                                        <option value="1" {{ request('type') == \App\Enums\StatusLabelEnum::DEPARTMENT->value ? 'selected' : '' }}>{{ \App\Enums\StatusLabelEnum::getString(\App\Enums\StatusLabelEnum::DEPARTMENT) }}</option>
                                        <option value="2" {{ request('type') == \App\Enums\StatusLabelEnum::STATUS->value ? 'selected' : '' }}>{{ \App\Enums\StatusLabelEnum::getString(\App\Enums\StatusLabelEnum::STATUS) }}</option>
                                        <option value="3" {{ request('type') == \App\Enums\StatusLabelEnum::POSITION->value ? 'selected' : '' }}>{{ \App\Enums\StatusLabelEnum::getString(\App\Enums\StatusLabelEnum::POSITION) }}</option>
                                    </select>
                                </div>

                                <div class="col-auto d-flex gap-2 mt-3 mt-md-0">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-filter"></i> Filter
                                    </button>
                                    @if (request()->hasAny(['type']))
                                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
                                            Reset
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>

                        <div class="mt-3 mt-md-0">
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createStatusLabel">
                                Add Label
                            </button>
                        </div>
                    </div>

                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>
                                    <a href="{{ route('status-label', array_merge(request()->except('page'), [
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($statusLabels as $statusLabel)
                                <tr>
                                    <td>{{ ($statusLabels->currentPage() - 1) * $statusLabels->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <span class="fw-bold">
                                            {{ $statusLabel->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold">
                                            @php
                                                switch ($statusLabel->type) {
                                                    case \App\Enums\StatusLabelEnum::DEPARTMENT->value:
                                                        $typeText = \App\Enums\StatusLabelEnum::getString(\App\Enums\StatusLabelEnum::DEPARTMENT);
                                                        break;
                                                    case \App\Enums\StatusLabelEnum::STATUS->value:
                                                        $typeText = \App\Enums\StatusLabelEnum::getString(\App\Enums\StatusLabelEnum::STATUS);
                                                        break;
                                                    case \App\Enums\StatusLabelEnum::POSITION->value:
                                                        $typeText = \App\Enums\StatusLabelEnum::getString(\App\Enums\StatusLabelEnum::POSITION);
                                                        break;
                                                    default:
                                                        $typeText = 'Unknown';
                                                }
                                            @endphp
                                            {{ $typeText }}
                                        </span>
                                    </td>


                                    <td>{{ \Carbon\Carbon::parse($statusLabel->created_at)->format('d M Y H:ia') }}</td>
                                    <td>

                                        <a href="#" class="edit-status-label" data-bs-toggle="modal"
                                            data-bs-target="#editStatusLabel" data-status-label-id="{{ $statusLabel->id }}">
                                            <i data-feather="edit-2" class="me-50"></i>
                                        </a>
                                        <form id="deleteFormStatusLabel" method="POST" action="{{ route('status-label.delete') }}" class="d-inline">
                                            @method('DELETE')
                                            @csrf
                                            <input type="text" name="status_label_id" id="delete-status_label_id" hidden>
                                            <button type="button" class="btn-link" style="border: none; background: none; padding: 0; margin: 0;"
                                               onclick="confirmDelete({{ $statusLabel->id }})">
                                               <i data-feather="trash-2" class="me-50"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mx-1 d-flex justify-content-end">
                        <nav aria-label="Page navigation">
                            <ul class="pagination mt-2">
                                <li class="page-item prev"><a class="page-link"
                                        style="pointer-events: {{ $statusLabels->currentPage() == 1 ? 'none' : '' }}"
                                        href="{{ $statusLabels->url($statusLabels->currentPage() - 1) }}"></a>
                                </li>
                                @for ($i = 1; $i <= $statusLabels->lastPage(); $i++)
                                    <li class="page-item {{ $i == $statusLabels->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $statusLabels->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="page-item next" disabled><a class="page-link"
                                        style="pointer-events: {{ $statusLabels->currentPage() == $statusLabels->lastPage() ? 'none' : '' }}"
                                        href="{{ $statusLabels->url($statusLabels->currentPage() + 1) }}"></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createStatusLabel" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">Add new Label</h1>
                    </div>

                    <div class="card-body">
                        <form class="form form-horizontal" action="{{ route('status-label.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="type">Type<span style="color: red"> * </span></label>
                                        </div>
                                        <div class="col-sm-9">
                                            <select id="type" name="type" class="form-control" required>
                                                <option value="" selected>-- Select Type --</option>
                                                <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>{{ \App\Enums\StatusLabelEnum::getString(\App\Enums\StatusLabelEnum::DEPARTMENT) }}</option>
                                                <option value="2" {{ old('type') == '2' ? 'selected' : '' }}>{{ \App\Enums\StatusLabelEnum::getString(\App\Enums\StatusLabelEnum::STATUS) }}</option>
                                                <option value="3" {{ old('type') == '3' ? 'selected' : '' }}>{{ \App\Enums\StatusLabelEnum::getString(\App\Enums\StatusLabelEnum::POSITION) }}</option>
                                            </select>
                                            @error('type')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="first-name">Label Name<span style="color: red"> * </span></label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" id="name" class="form-control" name="name"
                                                placeholder="Label Name"  required/>
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="text_color">Text Color</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="color" id="text_color" class="form-control form-control-color" name="text_color"
                                                value="#000000" title="Choose your text color" />
                                            @error('text_color')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Background Color Field -->
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="background_color">Background Color</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="color" id="background_color" class="form-control form-control-color" name="background_color"
                                                value="#ffffff" title="Choose your background color" />
                                            @error('background_color')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-9 offset-sm-3">
                                    @if (session('error'))
                                        <div class="text-danger">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                    <button type="submit" class="btn btn-primary me-1">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editStatusLabel" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">Update Status Label</h1>
                    </div>

                    <div class="card-body">
                        <form class="form form-horizontal" id="edit-status-label-form"  action="" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="type_edit">Type<span style="color: red"> * </span></label>
                                        </div>
                                        <div class="col-sm-9">
                                            <select id="type_edit" name="type" class="form-control" required>
                                                <option value="" selected>-- Select Type --</option>
                                                <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>{{ \App\Enums\StatusLabelEnum::getString(\App\Enums\StatusLabelEnum::DEPARTMENT) }}</option>
                                                <option value="2" {{ old('type') == '2' ? 'selected' : '' }}>{{ \App\Enums\StatusLabelEnum::getString(\App\Enums\StatusLabelEnum::STATUS) }}</option>
                                                <option value="3" {{ old('type') == '3' ? 'selected' : '' }}>{{ \App\Enums\StatusLabelEnum::getString(\App\Enums\StatusLabelEnum::POSITION) }}</option>
                                            </select>
                                            @error('type')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="first-name">Label Name</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" id="first-name" class="form-control" name="name"
                                                placeholder="Name" />
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="edit-text-color">Text Color</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="color" id="edit-text-color" class="form-control form-control-color"
                                                name="text_color" />
                                            @error('text_color')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Background Color Field -->
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="edit-background-color">Background Color</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="color" id="edit-background-color"
                                                class="form-control form-control-color" name="background_color" />
                                            @error('background_color')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-9 offset-sm-3">
                                    @if (session('error'))
                                        <div class="text-danger">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                    <button type="submit" class="btn btn-primary me-1">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                </div>
                            </div>
                        </form>
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
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/pagination/components-pagination.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editLinks = document.querySelectorAll('.edit-status-label');

            editLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const statusLabelId = this.dataset.statusLabelId;

                    const editForm = document.getElementById('edit-status-label-form');
                    editForm.action = `/status-label/${statusLabelId}`;

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

                    axios.get(`/status-label/${statusLabelId}/edit`)
                        .then(response => {
                            const labelData = response.data;

                            document.getElementById('first-name').value = labelData.name;
                            document.getElementById('edit-text-color').value = labelData.text_color;
                            document.getElementById('edit-background-color').value = labelData.background_color;
                            document.getElementById('type_edit').value = labelData.type;
                        })
                        .catch(error => {
                            console.error('Error fetching user data', error);
                        });
                });
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function(){
            setTimeout(function(){
                $("#success-alert").alert('close');
            }, 3000);
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        function confirmDelete(statusLabelId) {
            document.getElementById('delete-status_label_id').value = statusLabelId;
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
        document.getElementById('deleteFormStatusLabel').submit();
    }
    </script>



@endsection
