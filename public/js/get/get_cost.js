
$('#form').on('submit', function (e) {
    e.preventDefault();
    var his = $(this),
        btn = his.find("button.submit"),
        data = $("#form").serialize(),
        preloader = $('.preloader-img');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: '/get_cost',
        type: 'POST',
        data: data,
        dataType: "html", //формат данных
        beforeSend: function () {
            preloader.fadeIn(300);
        },

        success: function (response) {
            preloader.delay(500).fadeOut('slow', function () {
                var res = JSON.parse(response);
                if (res.answer === 'ok') {
                    $('div#cost').empty();
                    var ar = String(res.date_book);
                    array = ar.split(',');
                    var inHTML = '';

                    $.each(array, function (key, value) {
                        var html = value + " руб.<br>";
                        inHTML += html;
                        // $('.files').html("<img class=\"img-thumbnail del\"  src=\"/images/" + value[key] + "\"  data-file=\"" + value[key] + "\" />");
                        $('div#cost').html(inHTML);
                    })
                    $('div#summ').html("итого: "+ res.nigh + " ноч./" + res.summ + " руб.<br>");

                } else {
                    alert('Ошибка Ошибка');
                }
            });
            return false;
        },
        error: function () {
            alert('Ошибка');
        }
    });
});

