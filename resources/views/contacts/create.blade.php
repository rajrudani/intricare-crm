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
                <a href="{{ route('contacts.index') }}" class="btn btn-warning"><span>Go To Home</span></a>
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
                            <label for="email">Email *</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone No. *</label>
                            <input type="text" class="form-control validate-phone" id="phone" name="phone" placeholder="+9187580XXXXX">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="profile_image">Profile Image *</label>
                            <input type="file" class="form-control file-control" id="profile_image" name="profile_image" accept="image/*">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="additional_file">Additional File</label>
                            <input type="file" class="form-control file-control" id="additional_file" name="additional_file">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <button type="button" class="btn btn-dark" id="btn-add-custom-row">+ Add Custom Field</button>
                    <div class="col-md-12 mt-3">
                        <div id="custom-field-container">
                             
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" id="btnSubmit" class="btn btn-primary mt-3">Save Contact</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const storeRoute = '{{ route('contacts.store') }}';
        const indexRoute = '{{ route('contacts.index') }}';
    </script>
@endsection

@vite(['resources/js/contacts/create.js'])