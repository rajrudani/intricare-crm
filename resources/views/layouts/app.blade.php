<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', '') - Intricare CRM</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/datatables/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/toaster/jquery.toast.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
    <div class="container-xl">
        <div class="table-responsive">
            <div class="table-wrapper">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/popper.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/toaster/jquery.toast.min.js') }}"></script>
    <script src="{{ asset('assets/custom.js') }}"></script>

    @yield('script')
</body>
</html>