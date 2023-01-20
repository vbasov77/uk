$(function () {
    $('body').on('click', '.del', function () {
        if (!confirm('Подтвердите удаление')) return false;
        var $this = $(this);
        file = $this.data('file');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/delete_img/room_id' + id,
            data: {file: file},
            success: function (res) {
                $this.fadeOut();
            },

            error: function () {
                alert('Ошибка!!!')
            }
        });
    });

    let myDropzone = new Dropzone("div#file", {
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "/upload_img/id" + id,
        maxFilesize: 2,
        maxFiles: 25,
        parallelUploads: 1,
        acceptedFiles: ".png,.jpg,.gif,.jpeg",
        dictInvalidFileType: "Разрешены к загрузке только файлы .png, .jpg, .gif, .jpeg",
        dictMaxFilesExceeded: "Максимум 20 фото",
        dictFileSizeUnits: "Максимум 2 MB",
        dictDefaultMessage: '<div class="dz-button" type="button">Нажмите здесь или перетащите сюда файлы для загрузки</div>',
        success: function (file, response) {
            var url = file.dataURL;
            var res = JSON.parse(response);
            var ar = String(res.fil);
            array = ar.split(',');
            var inHTML = '';
            if (res.answer === 'error') {
                $('.preview').html(' <div class="alert alert-danger alert-dismissible" role="alert" > <button type="button" class="close" data-dismiss="allert"' +
                    'aria-label="Close" > <span aria-hidden="true" >&times;</span></button>' + res.mess + '</div>');
            } else {
                $('div#file').empty();
                $.each(array, function (key, value) {
                    $('div#files').empty();
                    var html = "<img class=\"img-thumbnail del\" src=\"/images/" + value + "\"  data-file=\"" + value + "\" />";
                    inHTML += html;
                    $('div#files').html(inHTML);
                })
            }
            this.removeFile(file);
        },

        init: function () {
            $(this.element).html(this.options.dictDefaultMessage);
        },

    });

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
            url: '/edit_room',
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
                        $('.preview').html(' <div class="alert alert-success alert-dismissible" role="alert" > <button type="button" class="close" data-dismiss="allert"' +
                            'aria-label="Close" > <span aria-hidden="true" >&times;</span></button> Данные сохранены</div>');
                    } else {
                        $('.preview').html(' <div class="alert alert-danger alert-dismissible" role="alert" > <button type="button" class="close" data-dismiss="allert"' +
                            'aria-label="Close" > <span aria-hidden="true" >&times;</span></button> Ошибка сохранения данных</div>');
                    }
                    myDropzone.removeAllFiles();
                });
                return false;
            },
            error: function () {
                alert('Ошибка');
            }
        });
    });
});


