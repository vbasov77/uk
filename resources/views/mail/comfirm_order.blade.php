
Здравствуйте, {{$data ['name_user']}}!<br>
<br>
Ваше бронирование Подтверждено. <br>
<br>
Адрес:<br>
Санкт-Петербург, пр. Обуховской обороны, д. 123А, №13<br>
<br>
Дата и время въезда: {{$data ['in']}} с 14.00<br>
Дата и время выезда: {{$data ['out']}} до 12.00<br>
<br>
Итого: {{$data ['sum']}} руб<br>
<br>
Для того, чтобы внести предоплату, войдите в свой личный кабинет <a href="{{request()->root()}}/profile" target="_blank">Здесь</a> .<br>
<br>
<br>
С уважением,<br>
администрация сайта {{config('app.name')}}!
