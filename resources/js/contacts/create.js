$(document).ready(function() {
    $("#contactForm").on("submit", function(e) {
        e.preventDefault();

        $('#btnSubmit').attr('disabled', true);
        $.ajax({
            url: storeRoute,
            type: "POST",
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function(response) {
                showToast(response.message, 'success');
                setInterval(() => {
                    window.location.href = indexRoute;
                }, 2000);
            },
            error: function (xhr) {
                $('#btnSubmit').attr('disabled', false);
                $('.error-msg').remove();
                $('input, textarea, select').removeClass('is-invalid');

                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        var errorMessage = $('<div class="error-msg text-danger"></div>').text(messages[0]);

                        if (field.startsWith('custom_fields')) {
                            var customFieldElement = $(`[name="custom_fields[`+ field.split('.')[1] +`][`+ field.split('.')[2] +`]"`);
                            customFieldElement.addClass('is-invalid').after(errorMessage);
                        }else{
                            var fieldElement = $('[name="' + field + '"]');
                            if (fieldElement.attr('type') === 'radio') {
                                fieldElement.closest('.form-group').append(errorMessage);
                            } else {
                                fieldElement.addClass('is-invalid').after(errorMessage);
                            }
                        }
                    });
                } else {
                    alert("Something went wrong. Please try again.");
                }
            }
        });
    });

    let fieldCounter = 0;
    // Add new custom field row
    $('#btn-add-custom-row').click(function() {
        $('#custom-field-container').append(createCustomFieldRow());
        fieldCounter++;
    });

    // Delete custom field row
    $(document).on('click', '.btn-delete-custom-row', function() {
        $(this).closest('.custom-field-row').remove();
    });

    function createCustomFieldRow() {
        const newRow = `
            <div class="row custom-field-row mb-2">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="custom_fields[${fieldCounter}][title]" placeholder="Title">
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" name="custom_fields[${fieldCounter}][value]" placeholder="Value">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger text-white btn-delete-custom-row">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        return newRow;
    }
});