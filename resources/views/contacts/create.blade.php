@extends('layouts.app')

@section('title')
    Create Contact
@endsection

@section('content')
    <div class="table-title">
        <div class="row">
            <div class="col-sm-4">
                <h2>Create <b>Contact</b></h2>
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
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name *</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone No. *</label>
                            <input type="text" class="form-control" id="phone" name="phone">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <label for="gender">Gender *</label>
                        <div class="form-group">
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="gender" value="Male">Male
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="gender" value="Female">Female
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="gender" value="Other">Other
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="profile_image">Profile Image *</label>
                            <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="additional_file">Additional File</label>
                            <input type="file" class="form-control" id="additional_file" name="additional_file">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary mt-3">Save Contact</button>
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
                
                $.ajax({
                    url: "{{ route('contacts.store') }}",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        alert(response.message);
                        window.location.href = "{{ route('contacts.index') }}";
                    },
                    error: function (xhr) {
                        $('.error-msg').remove();
                        $('input, textarea, select').removeClass('is-invalid');

                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                var fieldElement = $('[name="' + field + '"]');
                                var errorMessage = $('<div class="error-msg text-danger"></div>').text(messages[0]);

                                if (fieldElement.attr('type') === 'radio') {
                                    fieldElement.closest('.form-group').append(errorMessage);
                                } else {
                                    fieldElement.addClass('is-invalid').after(errorMessage);
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
@endsection
