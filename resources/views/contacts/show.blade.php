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
<div class="container">

@if(Session::has('success'))
<div id="success-alert" class="alert alert-success" style="padding: 15px;">
    {{ Session::get('success') }}
</div>
@endif
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><strong>Name:</strong> {{ $contact->name }}</h5>
            <p class="card-text"><strong>Email:</strong> {{ $contact->email }}</p>
            <p class="card-text"><strong>Phone:</strong> {{ $contact->phone }}</p>
            <p class="card-text"><strong>Company:</strong> {{ $contact->company }}</p>
            <p class="card-text"><strong>Subject:</strong> {{ $contact->subject }}</p>
            <p class="card-text"><strong>Sub Subject:</strong> {{ $contact->subsubject }}</p>
            <p class="card-text"><strong>Message:</strong> {{ $contact->message }}</p>
            <div class="card-text d-flex">
                <strong style="margin-right: 10px; margin-bottom: 15px;">Status:</strong>
                <form action="{{ route('contacts.update-status', $contact->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <select name="status" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                        @foreach (App\Enums\ContactStatusEnum::cases() as $status)
                            <option value="{{ $status->value }}" {{ $contact->status === $status->value ? 'selected' : '' }}>
                                {{ $status->value }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <a href="{{ route('contacts') }}" class="btn btn-primary">Back to List</a>
        </div>
    </div>
</div>

@endsection


