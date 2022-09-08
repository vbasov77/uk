let button = $(':input[type="submit"]');
button.prop('disabled', true);
$('input[type="email"]').keyup(function() {
    if(validateEmail($(this).val())) {
        button.prop('disabled', false);
    }
    else{
        button.prop('disabled', true);
    }
});

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}
