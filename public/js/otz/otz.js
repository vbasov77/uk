
$('#submit').attr('disabled', true);
$('#otz').change(function () {
    if ($('#otz').val() != '') {
        $('#submit').attr('disabled', false);
    } else {
        $('#submit').attr('disabled', true);
    }
});

