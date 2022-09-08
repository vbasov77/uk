@extends('layouts.app')
@section('content')
    <center>
        <div class="container mt-5 mb-5">
            <h3>Проверкае данных:</h3><br>
            Id: {{$res [0] ['id']}} <br>
            Даты: {{$res [0] ['no_in'] . ' - ' . $res [0] ['no_out']}} <br>
            ФИО: {{$res [0] ['name_user']}} <br>
            Телефон: {{$res [0] ['phone_user']}} <br>
            Email: {{$res [0] ['email_user']}}<br>
            Сумма: {!!$res [0] ['summ'] !!} <br>
            Статус: @if (!empty($res[0]['pay'] == 0))
                Не оплачен<br>
            @else
                Оплачен<br>
                @php
                    $os = explode(';', $res [0] ['info_pay']);
                    $ost = $res [0]['summ'] - $os[2];
                @endphp
                Остаток: {{$ost}} руб.<br>
            @endif
            Ночей: {{$nights}}<br>
            <br>
            Гости:<br>
            @foreach ($user_info as $item)
                <div>
                  {!! $item!!} <br>
                </div>
            @endforeach
            <br>

            @if (!empty($res [0] ['more_book']))
                @php
                    $info = explode(',', $res [0] ['more_book']);
                @endphp

                @foreach($info as $item)
                   {!! $item !!}<br>

                @endforeach
            @endif
            <br>
            <br>
            @if($res [0]['confirmed'] == 0)
                <div>
                    <button class="btn btn-outline-success btn-sm"
                            onclick="window.location.href = '{{route('order.confirm', ['id'=> $res[0]->id])}}';">
                        Подтвердить
                    </button>
                </div>
                <button class="btn btn-outline-secondary btn-sm"
                        style="margin: 5px"
                        onclick="window.location.href = '{{route('order.reject', ['id'=> $res[0]->id])}}';">
                    Отклонить
                </button>
            @endif

        </div>
    </center>
@endsection
