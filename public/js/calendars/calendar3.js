var input = document.getElementById('input-id');
var arr_datebook = datebook.split(',');
var datepicker = new HotelDatepicker(input, {
    inline: true,
    clearButton: true,
    topBar: false,
    moveBothMonths: true,
    format: 'DD.MM.YYYY',
    startOfWeek: 'monday',
    // selectForward: true,
    disabledDates: arr_datebook,
    i18n: {
        selected: 'Ваше пребывание:',
        night: 'Нч',
        nights: 'Нч',
        button: 'Закрыть',
        'checkin-disabled': 'Регистрация отключена',
        'checkout-disabled': 'Выезд отключен',
        'day-names-short': ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
        'day-names': ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
        'month-names-short': ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'],
        'month-names': ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
        'error-more': 'L\'диапазон дат должен быть не более 1 ночи',
        'error-more-plural': 'L\'диапазон дат не должен быть больше, чем %d ночей',
        'error-less': 'L\'диапазон дат должен быть не менее 1 ночи',
        'error-less-plural': 'L\'диапазон дат должен быть не менее %d ночей',
        'info-more': 'Пожалуйста, выберите диапазон дат больше 1 ночи',
        'info-more-plural': 'Пожалуйста, выберите диапазон дат больше, чем %d ночей',
        'info-range': 'Пожалуйста, выберите диапазон дат между %d и %d ночами',
        'info-default': 'Пожалуйста, выберите диапазон дат'
    }


});

