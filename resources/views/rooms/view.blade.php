@extends('layouts.app')
@section('content')
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <section>
        <div class="container">
            <div class="row  justify-content-center text-center">
                <div style="padding: 20px">
                    <h3>{{$data ['name_room']}}</h3><br>
                </div>
                <div class="row">
                    <div class="col-xl-8">
                        <div id="carusel" class="carousel slide" data-ride="carousel">
                            <ul class="carousel-indicators">
                                @for ($s = 0; $s < count($photo); $s++)
                                    @php
                                        if ($s == 0){
                                             $active = "active";
                                        } else {$active = "";}
                                    @endphp
                                    <li data-target="#carusel" data-slide-to="{{$s}}" class="{{$active}}"></li>
                                @endfor
                            </ul>
                            <div class="carousel-inner">
                                @for ($i = 0; $i < count($photo); $i++)
                                    @php
                                        if ($i == 0){
                                            $carusel = "carousel-item active";
                                             } else {$carusel = "carousel-item";}
                                    @endphp
                                    <div class="{{$carusel}}">
                                        <img src="{{ asset('images/' . $photo[$i])}}" alt="">
                                    </div>

                                @endfor
                                <a class="carousel-control-prev" href="#carusel" data-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </a>
                                <a class="carousel-control-next" href="#carusel" data-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </a>
                            </div>
                        </div>

                    </div>

                    <div class="col-xl-4">
                        @if (!empty($data['price']))
                            <div style="font-size: 30px; margin-bottom: 15px; opacity: .7; margin-top: 45px; ">
                                <b> От {{ $data ['price'] }} <i class="fa fa-rub"></i></b>
                            </div>
                        @endif
                        @if (!empty($data['service'])  )
                            <h3 style="padding: 15px">Сервис</h3>
                            @php
                                $service = explode(',', $data['service']);
                            @endphp

                            @foreach($service as $value)
                                <div style="font-size: 23px;">
                                    <i class="fa fa-check"></i> {{$value }}<br>
                                </div>
                            @endforeach

                        @endif
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-6">
                        <h3 style="padding: 15px">Забронировать</h3>
                        <form action="/add_calendar" method="post">
                            @csrf
                            <input type="hidden" name="id" value="<?=$data['id']?>">
                            <label for="date_book"><b>Выберете дату:</b></label>
                            <div>
                                <input id="input-id" style="margin-bottom: 15px; text-align: center "
                                       name="date_book"
                                       type="text"
                                       class="form-control" value="{{session('date_book') ?? ''}}"
                                       placeholder="Нажмите для выбора даты" autocomplete="off"
                                       required>
                            </div>
                            <div>
                                <label for="guests"><b>Количество гостей:</b></label><br>
                                @for ($i = 0; $i < $data->capacity; $i++)
                                    <div>
                                        <input required class="radio" type="radio" name="guests" value="{{ $i + 1 }}"
                                                @php
                                                    if (!empty(session('people')) and session('people') == $i + 1) {
                                                        echo 'checked';
                                                    }
                                                @endphp
                                        >
                                        {{$i + 1}} чел. <br>
                                    </div>
                                @endfor
                            </div>
                            <div>
                                <input class="btn btn-outline-success" type="submit" value="Забронировать">
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-6">
                        <h3 style="padding: 15px">Подробнее</h3>

                        <div style="font-size: 18px">
                            {{$data->text_room}}
                        </div>
                    </div>
                </div>

                <br>
                <br>
                <div class="row justify-content-center text-center">
                    <div class="col-12">
                        @if (!empty($data->video))
                            <div>
                                <iframe width="560" height="315" src="{{$data->video}}" title="YouTube video player"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                            </div>
                        @endif
                    </div>
                </div>
                <br>
            </div>

        </div>
    </section>
    <div style="margin-top: 25px; margin-bottom: 25px; text-align: center;">
        <h2>На карте</h2>
    </div>
    <div id="map" style="width: 100%; height: 400px"></div>
    </section>
    <section>
        <div class="row justify-content-center text-center">
            @if($role == 1)
                <div>
                    <button class="btn btn-success btn-sm" style="color: white; margin-top: 25px"
                            onclick="window.location.href = '{{route('room.edit', ['id'=>$data->id])}}'">
                        Редактировать номер
                    </button>
                </div>
            @endif
        </div>
    </section>
    @push('scripts')
        <script src="{{ asset('js/fecha.min.js') }}" defer></script>
        <link href="{{ asset('css/hotel-datepicker.css') }}" rel="stylesheet">
        <script src="{{ asset('js/hotel-datepicker.min.js') }}" defer></script>
        <script>
            var datebook = @json($date_book);
            var start = @json($start);
            var min = @json((int)$rules[1]);
            var max = @json((int)$rules[2]);
        </script>
        <script src="{{ asset('js/calendars/calendar2.js') }}" defer></script>
        <script src="{{ asset('js/get/get_cost.js') }}" defer></script>


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
                    zoom: 9,
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


                var myPlacemark = new ymaps.Placemark([{{$data-> coordinates}}], {
                        // Хинт показывается при наведении мышкой на иконку метки.
                        hintContent: 'Содержимое всплывающей подсказки',
                        // Балун откроется при клике по метке.
                        balloonContent: '<center><div>{{$data-> address}}<br>{{$data-> price}}<br></div></center>'
                    });

                // После того как метка была создана, добавляем её на карту.
                myMap.geoObjects.add(myPlacemark);

            }
        </script>
    @endpush
@endsection
