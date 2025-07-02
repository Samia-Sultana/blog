@extends('layouts/contentLayoutMaster')

@section('title', 'Generate Execl')

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
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Generate Execl</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('export.create') }}">
                    @csrf
                    <div class="filter-section">
                        <div class="row filter-row">
                            <div class="col-md-3">
                                <label class="form-label" for="model_name">Select Module</label>
                                <select class="select2 form-select" id="model_name" name="model_name">
                                    <option value="">All Module</option>
                                    <option value="Contact">Contact</option>
                                    <option value="AIPageContact">AI Page Contact</option>
                                    <option value="CountryOrCityWisePageContent">AI SEO Page</option>
                                    <option value="SubscribeViserX">Subscribe Hossain Litigation & Law</option>
                                    <option value="CompanyDeck">Company Deck</option>
                                    <option value="Application">Job Application</option>
                                </select>
                            </div>
                            <div class="col-md-3 mt-2">
                                <input type="submit" class="btn btn-primary waves-effect waves-float waves-light" value="Generate">
                            </div>
                        </div>
                    </div>
                </form>
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
                            <th>Module Name</th>
                            <th>File Name</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse($exports as $modelName => $exportGroup)
                                @foreach($exportGroup as $file)
                                    <tr>
                                        <td>{{ $modelName }}</td>

                                        <td>{{ $file['filename'] }}</td>
                                        <td>{{ $file['created_at'] }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ $file['download_url'] }}" class="btn btn-success">
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                                <button class="btn btn-danger delete-export"
                                                        style="margin-left: 5px;"
                                                        data-url="{{ $file['delete_url'] }}">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No export files found</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
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
    <script>
        $(document).ready(function() {
            $('.delete-export').click(function() {
                if (confirm('Are you sure you want to delete this export file?')) {
                    const url = $(this).data('url');

                    $.ajax({
                        url: url,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function() {
                            location.reload();
                        },
                        error: function() {
                            alert('Failed to delete file');
                        }
                    });
                }
            });
        });
        </script>
        <script>
            $(document).ready(function() {
                setTimeout(function() {
                    $("#success-alert").alert('close');
                }, 3000);
            });
        </script>
@endsection
