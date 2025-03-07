@extends('layouts.app')

@section('title')
    Contacts
@endsection

@section('content')
    <div class="table-title">
        <div class="row">
            <div class="col-sm-4">
                <h2>All <b>Contacts</b></h2>
            </div>
            <div class="col-sm-8">
                <a href="{{ route('contacts.create') }}" class="btn btn-primary">Add New Contact</a>
            </div>
        </div>
    </div>
    <div class="table-filter">
        <div class="row">
            <div class="col-sm-12">
                <button type="button" class="btn btn-warning d-none" id="clearFilterBtn">Clear</i></button>
                <button type="button" class="btn btn-dark" id="searchBtn"><i class="fa fa-search"></i></button>
                
                <div class="filter-group">
                    <label>Gender</label>
                    <select class="form-control" id="gender-filter">
                        <option value="">All</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Email</label>
                    <input type="text" class="form-control" id="email-filter">
                </div>
                <div class="filter-group">
                    <label>Name</label>
                    <input type="text" class="form-control" id="name-filter">
                </div>
                <span class="filter-icon" ><i class="fa fa-filter"></i></span>
            </div>
        </div>
    </div>
    <table class="table table-striped table-hover" id="contactsTable">
        <thead>
            <tr>
                {{-- <th>#ID</th> --}}
                <th>Profile</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Gender</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <div class="modal fade" id="mergeContactModal" tabindex="-1" aria-labelledby="mergeContactTitle" aria-hidden="false"></div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            var datatable = $('#contactsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('contacts.index') }}',
                    data: function (d) {
                        d.filters = {
                            gender: $('#gender-filter').val(),
                            email: $('#email-filter').val(),
                            name: $('#name-filter').val()
                        };
                    }
                },
                columns: [
                    // {data: 'id', name: 'id'},
                    {data: 'profile_image', name: 'profile_image', render: function(imagePath) {
                        return '<img src="' + imagePath + '" class="avatar" alt="Profile Image" height="30px" width="30px">';
                    }, orderable: false, searchable: false },
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'gender', name: 'gender', class: 'text-center'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'}
                ],
                searching: false,
                lengthChange: false,
                order: [[1, 'asc']],
                pageLength: 25
            });

            // Search button click event
            $('#searchBtn').click(function () {
                datatable.ajax.reload();
                $('#clearFilterBtn').removeClass('d-none');
            });

            // Clear filters functionality
            $('#clearFilterBtn').click(function () {
                $('#gender-filter').val('');
                $('#email-filter').val('');
                $('#name-filter').val('');

                datatable.ajax.reload();

                $(this).addClass('d-none');
            });

            $(document).on('click', '.delete-contact', function () {
                let contactId = $(this).data('id');
            
                if (!confirm('Are you sure you want to delete this contact?')) return;
            
                $.ajax({
                    url: '{{ route('contacts.destroy', '') }}/' + contactId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        datatable.ajax.reload();

                        showToast(response.message, 'success');
                    },
                    error: function (xhr) {
                        alert('Error: ' + xhr.responseJSON.error);
                    }
                });
            });

            $(document).on('click', '.merge-contact', function() {
                let modalElement = $('#mergeContactModal');
                let contactId = $(this).data('id');

                $.ajax({
                    url: '{{ route("contacts.merge-contact-modal", "") }}/' + contactId,
                    type: 'GET',
                    success: function(response) {
                        modalElement.html(response); 

                        let modalInstance = new bootstrap.Modal(modalElement[0]); 
                        modalInstance.show(); 
                    },
                    error: function(error) {
                        console.error('Error loading modal content:', error);
                    }
                });
            });
        });
    </script>
@endsection
