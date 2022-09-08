SPB-R.RU<br>
Новое бронирование<br>
<br>
Даты: {{$data['in'] . ' - ' . $data ['out']}}<br>
<br>
Забронировал: {{$data['name_user']}}<br>
<br>
Пройдите по ссылке <a href="{{request()->root()}}/order/{{$data['id']}}/verification">Здесь</a>
<br>

С уважением,<br>
администрация сайта {{config('app.name')}}!


