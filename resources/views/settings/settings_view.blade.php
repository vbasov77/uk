@extends('layouts.app')
@section('content')

    <section class="about-section text-center" id="about">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            Расписание
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Редактировать расписание календаря</h5>
                            <div class="card-footer">
                                <button class="btn btn-outline-success"
                                        onclick="window.location.href = '{{route('schedule')}}';">Редактировать
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            Правила
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Настройка правил календаря</h5>
                            <p class="card-text">День начала брони, Мин количество дней, Макс. количество дней... </p>
                            <div class="card-footer">
                                <button class="btn btn-outline-success"
                                        onclick="window.location.href = '{{route('rules_settings')}}';">Редактировать
                                </button>
                            </div>

                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            Главная
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Настройка главной страницы</h5>
                            <p class="card-text">Заголовок: {{ $front[0]}}, Цвет зпголовка: {{$front[1]}}, Шрифт
                                заголовк: {{$front[2]}}, Фото фона: <img src='{{ url("img/bg_image/$front[3]")}}'
                                                                          width="100px" height="auto"></p>
                            <div class="card-footer">
                                <button class="btn btn-outline-success"
                                        onclick="window.location.href = '{{route('front_settings')}}';">Редактировать
                                </button>
                            </div>

                        </div>
                    </div>
`

                </div>
            </div>
        </div>
    </section>

@endsection
