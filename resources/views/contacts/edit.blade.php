@extends('layouts.app')

@section('title')
    Edit Contact
@endsection

@section('content')
    <div class="table-title">
        <div class="row">
            <div class="col-sm-4">
                <h2>Edit <b>Contact</b></h2>
            </div>
            <div class="col-sm-8">
                <a href="{{ route('contacts.index') }}" class="btn btn-warning"><span>All Contacts</span></a>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <form id="contactForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name *</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ $contact->name }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ $contact->email }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone No. *</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                value="{{ $contact->phone }}">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <label for="gender">Gender *</label>
                        <div class="form-group">
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="gender" value="Male"
                                        @if ($contact->gender == 'Male') @checked(true) @endif>Male
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="gender" value="Female"
                                        @if ($contact->gender == 'Female') @checked(true) @endif>Female
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="gender" value="Other"
                                        @if ($contact->gender == 'Other') @checked(true) @endif>Other
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="profile_image">Profile Image *</label>
                            <div class="m-2">
                                <img src="{{ $contact->profile_imagepath }}" alt="Profile Image" class=""
                                    height="75px" width="110px">
                            </div>
                            <input type="file" class="form-control file-control" id="profile_image" name="profile_image"
                                accept="image/*">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="additional_file">Additional File</label>
                            @if (!empty($contact->additional_file))
                                <div class="m-2">
                                    <a href="{{ $contact->additional_filepath }}" download>
                                        <i class="fa fa-file"></i> Download File
                                    </a>
                                </div>
                            @endif
                            <input type="file" class="form-control file-control" id="additional_file"
                                name="additional_file">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <button type="button" class="btn btn-dark" id="btn-add-custom-row">+ Add Custom Field</button>
                    <div class="col-md-12 mt-3">
                        <div id="custom-field-container">
                            @php
                                $fieldCounter = 0;
                            @endphp
                            @isset($contact->custom_fields)
                                @forelse (json_decode($contact->custom_fields) as $customField)
                                    <div class="row custom-field-row mb-2">
                                        <div class="col-md-4">
                                            <input type="text" class="form-control"
                                                name="custom_fields[{{ $fieldCounter }}][title]"
                                                value="{{ $customField->title }}" placeholder="Title">
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control"
                                                name="custom_fields[{{ $fieldCounter }}][value]"
                                                value="{{ $customField->value }}" placeholder="Value">
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-danger text-white btn-delete-custom-row">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @php
                                        $fieldCounter++;
                                    @endphp
                                @empty
                                @endforelse
                            @endisset
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" id="btnSubmit" class="btn btn-primary mt-3">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $("#contactForm").on("submit", function(e) {
                e.preventDefault();

                $('#btnSubmit').attr('disabled', true);
                $.ajax({
                    url: "{{ route('contacts.update', $contact->id) }}",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        showToast(response.message, 'success');
                        setInterval(() => {
                            window.location.href = "{{ route('contacts.index') }}";
                        }, 2000);
                    },
                    error: function(xhr) {
                        $('#btnSubmit').attr('disabled', false);
                        $('.error-msg').remove();
                        $('input, textarea, select').removeClass('is-invalid');

                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                var errorMessage = $(
                                        '<div class="error-msg text-danger"></div>')
                                    .text(messages[0]);

                                if (field.startsWith('custom_fields')) {
                                    var customFieldElement = $(`[name="custom_fields[` +
                                        field.split('.')[1] + `][` + field.split(
                                            '.')[2] + `]"`);
                                    customFieldElement.addClass('is-invalid').after(
                                        errorMessage);
                                } else {
                                    var fieldElement = $('[name="' + field + '"]');
                                    if (fieldElement.attr('type') === 'radio') {
                                        fieldElement.closest('.form-group').append(
                                            errorMessage);
                                    } else {
                                        fieldElement.addClass('is-invalid').after(
                                            errorMessage);
                                    }
                                }
                            });
                        } else {
                            alert("Something went wrong. Please try again.");
                        }
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            let fieldCounter = {{ $fieldCounter++ }};

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
    </script>
@endsection
