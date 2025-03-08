<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', '') - Intricare CRM</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>
<body>
    <div class="container-xl">
        <div class="table-responsive">
            <div class="table-wrapper">
                @yield('content')
            </div>
        </div>
    </div>

    @yield('script')

    <script>
        $('.validate-phone').on('input', function() {
            var phoneInput = $(this).val();
            
            phoneInput = phoneInput.replace(/[^+\d]/g, '');
            if (!phoneInput.startsWith('+')) {
                phoneInput = '+' + phoneInput.replace(/^/, '');
            }

            $(this).val(phoneInput);
        });

        function showToast(message, type){
            if(type == 'error'){
                bgColor = '#dc3545';
                hideAfter = 5000;
            }else{
                bgColor = 'black';
                hideAfter = 2000;
            }
            $.toast({
                text: message, 
                heading: type.charAt(0).toUpperCase() + type.slice(1), 
                icon: type, 
                showHideTransition: 'fade', 
                allowToastClose: true, 
                hideAfter: hideAfter, 
                stack: 5, 
                position: 'bottom-left', 
                textAlign: 'left',  
                loader: true,  
                loaderBg: '#d1e50c',  
                bgColor: bgColor, 
                textColor: 'white', 
            });
        }
    </script>
</body>

</html>