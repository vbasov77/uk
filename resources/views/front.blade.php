@extends('layouts.app')
@section('content')
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    @php
    @endphp
    <section class="pt-5 pb-5 mt-0 align-items-center d-flex bg-dark"
             style="background-size: cover;background-position: center; background-image: url({{ url("img/bg_image/$front[3]")}});">
        <div class="container-fluid">
            <div class="row  justify-content-center align-items-center d-flex text-center h-100">
                <div class="col-12 col-md-8  h-50 ">
                    <div class="block_bg">
                        <h1 style="color: {{$front[1]}}; font-family: {{ $front[2]}};"
                            class="display-2  mb-2 mt-5 fro">
                            <strong>{{$front[0]}}</strong></h1>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{route('search.room')}}" method="post">
                            @csrf
                            <div class="row gx-0 mb-5 mb-lg-0 justify-content-center">
                                <div class="col-8">
                                    <label for="date_book" style="color: white; width: 90%"><b>Выберете
                                            дату:</b></label>
                                    <div class="front">
                                        <input id="input-id" name="date_book" type="text"
                                               class="form-control text-center" style="display:inline"
                                               value="{{session('date_book') ?? '' }}"
                                               placeholder="Нажмите для выбора даты" autocomplete="off"
                                               readonly="readonly"
                                               required>
                                    </div>
                                    <br>
                                    <div>
                                        <center>
                                            <input class="form-control col-5 text-center" name="people"
                                                   placeholder="Количество гостей" type="text"
                                                   value="{{session('people') ?? '' }}"
                                                   onkeypress="return (event.charCode >= 48 && event.charCode <= 57 && /^\d{0,3}$/.test(this.value));">
                                        </center>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <input class="btn btn-danger" type="submit" style="color: white" value="Продолжить">
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <section style="margin-top: 40px" class="section text-center">
        <div class="container-fluid px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                @foreach($data as $value)
                    <?php
                    if (!empty($value['photo_room'])) {
                        $p = explode(',', $value ['photo_room']);
                        $photo = $p[0];
                    } else {
                        $photo = "no_image/no_image.jpg";
                    }
                    ?>
                    <div class="card" style="width: 18rem;">
                        <img src="{{ asset("images/$photo") }}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <div style="float: left; opacity: .6; ">
                                @for($i = 0; $i < $value ['capacity']; $i++)
                                    <i class="fa fa-user"></i>
                                    {{--                        <p class="card-text"><?= $value['text_room']?></p>--}}
                                @endfor
                                @if(!empty($value ['price'] ))
                                    &nbsp;<b>От:<?= $value ['price']  ?></b><i class="fa fa-rub"></i>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-outline-success"
                                    onclick="window.location.href = '{{route('num.id', ['id'=>$value ['id']])}}';">Подробнее
                            </button>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
        <script src="{{ asset('js/calendars/calendar.js') }}" defer></script>
    </section>
    <section>
        <div style="margin-top: 25px; margin-bottom: 25px; text-align: center;">
            <h2>На карте</h2>
        </div>
        <div id="map" style="width: 100%; height: 400px"></div>
    </section>
    @push('scripts')
        <script src="{{ asset('js/fecha.min.js') }}" defer></script>
        <link href="{{ asset('css/hotel-datepicker.css') }}" rel="stylesheet">
        <script src="{{ asset('js/hotel-datepicker.min.js') }}" defer></script>

        <script>
            var start = @json($start);
            var min = @json((int)$rules[1]);
            var max = @json((int)$rules[2]);
        </script>
        <script src="{{ asset('js/calendars/calendar.js') }}" defer></script>


        <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
        <script>
            // Функция ymaps.ready() будет вызвана, когда
            // загрузятся все компоненты API, а также когда будет готово DOM-дерево.
            ymaps.ready(init);

            function init() {
                // Создание карты.
                // https://tech.yandex.ru/maps/doc/jsapi/2.1/dg/concepts/map-docpage/
                var myMap = new ymaps.Map("map", {
                    // Координаты центра карты.
                    // Порядок по умолчнию: «широта, долгота».
                    center: [59.9386, 30.3141],
                    // Уровень масштабирования. Допустимые значения:
                    // от 0 (весь мир) до 19.
                    zoom: 8,
                    // Элементы управления
                    // https://tech.yandex.ru/maps/doc/jsapi/2.1/dg/concepts/controls/standard-docpage/
                    controls: [

                        'zoomControl', // Ползунок масштаба
                        'rulerControl', // Линейка
                        'routeButtonControl', // Панель маршрутизации
                        'trafficControl', // Пробки
                        'typeSelector', // Переключатель слоев карты
                        'fullscreenControl', // Полноэкранный режим

                        // Поисковая строка
                        new ymaps.control.SearchControl({
                            options: {
                                // вид - поисковая строка
                                size: 'large',
                                // Включим возможность искать не только топонимы, но и организации.
                                provider: 'yandex#search'
                            }
                        })

                    ]
                });

                // Добавление метки
                // https://tech.yandex.ru/maps/doc/jsapi/2.1/ref/reference/Placemark-docpage/
                        @foreach($objects as $obj)

                var myPlacemark = new ymaps.Placemark([{{$obj['coordinates']}}], {
                        // Хинт показывается при наведении мышкой на иконку метки.
                        hintContent: 'Содержимое всплывающей подсказки',
                        // Балун откроется при клике по метке.
                        balloonContent: '<center><div>{{$obj->address}}<br>от {{$obj->price}} <i style="opacity: .8;" class="fa fa-rub"></i><br><a href="{{route('num.id',['id' => $obj-> id])}}" class="btn btn-outline-success btn-sm"> Перейти</a> </div></center>'
                    });

                // После того как метка была создана, добавляем её на карту.
                myMap.geoObjects.add(myPlacemark);
                @endforeach
            }
        </script>
    @endpush
@endsection
