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
                <a href="{{ route('contacts.index') }}" class="btn btn-warning"><span>Go To Home</span></a>
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
                </div>
                <div class="row mt-1">
                    @php
                        $mergedData = json_decode($contact->merged_data, true) ?? ['emails' => [], 'phones' => []];
                    @endphp
                    @empty(!$mergedData['emails'])
                        <div class="col-md-6">
                            <b><span class="mr-1"><i class="fa fa-envelope"></i></span> Secondary Emails</b>
                            <div class="mt-1">
                                @foreach ($mergedData['emails'] as $index => $email)
                                    <div class="input-group mb-2">
                                        <input type="email" class="form-control" name="merged_emails[]"
                                            value="{{ $email }}" required>
                                        <button type="button" class="btn btn-danger remove-field">✖</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endempty
                    @empty(!$mergedData['phones'])
                        <div class="col-md-6">
                            <b><span class="mr-1"><i class="fa fa-phone"></i></span> Secondary Phones</b>
                            <div class="mt-1">
                                @foreach ($mergedData['phones'] as $index => $phone)
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" name="merged_phones[]"
                                            value="{{ $phone }}" required>
                                        <button type="button" class="btn btn-danger remove-field">✖</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endempty
                </div>
                <hr>
                <div class="row">
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
                                            @if(isset($customField->action) && $customField->action != 'original')
                                                <span class="text-danger">{{ ucfirst($customField->action) }} Field</span>
                                            @endif
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
        let fieldCounter  =  {{ $fieldCounter++ }};
        const updateRoute = '{{ route('contacts.update', $contact->id) }}';
        const indexRoute  = '{{ route('contacts.index') }}';
    </script>
@endsection

@vite(['resources/js/contacts/edit.js'])