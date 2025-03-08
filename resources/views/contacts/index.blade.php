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
        @include('contacts.partials.filters')
    </div>

    <table class="table table-striped table-hover" id="contactsTable">
        <thead>
            <tr>
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
        const indexRoute = '{{ route('contacts.index') }}';
        const destroyRoute = '{{ route('contacts.destroy', '') }}';
        const mergeContactRoute = '{{ route('contacts.merge-contact-modal', '') }}';
    </script>
@endsection

@vite(['resources/js/contacts/index.js'])