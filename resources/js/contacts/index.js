$(document).ready(function () {
    var datatable = $('#contactsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: indexRoute,
            data: function (d) {
                d.filters = {
                    gender: $('#gender-filter').val(),
                    email: $('#email-filter').val(),
                    name: $('#name-filter').val(),
                    visibility: $('#visibility-filter').val()
                };
            }
        },
        columns: [
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
        $('#visibility-filter').val('');

        datatable.ajax.reload();

        $(this).addClass('d-none');
    });

    $(document).on('click', '.delete-contact', function () {
        let contactId = $(this).data('id');
    
        if (!confirm('Are you sure you want to delete this contact?')) return;
    
        $.ajax({
            url: destroyRoute + '/' + contactId,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
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
            url: mergeContactRoute + '/' + contactId,
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