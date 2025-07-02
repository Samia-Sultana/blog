@extends('layouts/contentLayoutMaster')

@section('title', 'Backup List')

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
                    <form action="#" method="get">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('backup.create') }}" class="btn btn-outline-primary">Create Backup</a>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Backup Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($backupFolders as $folder)
                                <tr>
                                    <td>{{ $folder }}</td>
                                    <td>
                                        <a href="{{ route('backup.download', ['folder' => $folder]) }}" class="btn btn-sm btn-primary">Download</a>
                                        <form id="deleteForm" method="POST" action="{{ route('backup.delete') }}" class="d-inline">
                                            @method('DELETE')
                                            @csrf
                                            <input type="text" name="backup_folder" id="backup_folder" hidden>
                                            <button type="button" class="btn btn-sm btn-primary"
                                               onclick="confirmDelete('{{ $folder }}')">
                                               Delete
                                            </button>
                                         </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/pagination/components-pagination.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

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
        function confirmDelete(folder) {
            document.getElementById('backup_folder').value = folder;
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
@endsection
