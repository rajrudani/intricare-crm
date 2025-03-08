$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
});

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