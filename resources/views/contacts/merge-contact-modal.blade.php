<div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
        <form method="POST" id="merge-contact-form">
            @csrf
            <div class="modal-header">
                <h6 class="modal-title">Merge Contacts</h6>
                <button type="button" id="btn-modal-close" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="contact_id">Contact</label>
                            <input type="text" class="form-control" value="{{ $contact->name }}" disabled>
                            <input type="hidden" name="contact_id" class="form-control" value="{{ $contact->id }}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="master_contact_id" class="form-label">Master Contact *</label>
                            <select class="form-control" name="master_contact_id" id="master_contact_id">
                                <option value="">Select Master Contact</option>
                                @forelse ($masterContacts as $mContact)
                                    <option value="{{ $mContact->id }}">{{ $mContact->name }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning">Merge Contacts</button>
            </div>
        </form>
    </div>
</div>

<script>
    $("#merge-contact-form").on("submit", function(e) {
        e.preventDefault();
        if (!confirm('Are you sure you want to merge this contact?')) return;
        
        $.ajax({
            url: "{{ route('contacts.merge-contacts') }}",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function(response) {
                showToast(response.message, 'success');
                
                $('#btn-modal-close').trigger('click');
            },
            error: function(xhr) {
                $('.error-msg').remove();
                $('select').removeClass('is-invalid');

                if (xhr.status === 422) {
                    $.each(xhr.responseJSON.errors, function(field, messages) {
                        $('[name="' + field + '"]').addClass('is-invalid').after(
                            $('<div class="error-msg text-danger"></div>').text(messages[0])
                        );
                    });
                } else {
                    alert("Something went wrong. Please try again.");
                }
            }
        });
    });
</script>
