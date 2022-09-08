@extends('layouts.app')
@section('content')

    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        .picker input#input-id {
            display: none;
        }

        .picker .datepicker__info.datepicker__info--feedback.datepicker__info--help {
            display: none;
        }

        .picker button#clear-input-id {
            display: none;
        }

        .picker .datepicker__topbar {
            display: none;
        }

        .picker div#datepicker-input-id {
            right: 0;
            left: 0;
            margin: auto;
        }

        div#datepicker-input-id {
            position: inherit;
        }
    </style>
    @php
        $_monthsList = [
"1"=>"Январь","2"=>"Февраль","3"=>"Март",
"4"=>"Апрель","5"=>"Май", "6"=>"Июнь",
"7"=>"Июль","8"=>"Август","9"=>"Сентябрь",
"10"=>"Октябрь","11"=>"Ноябрь","12"=>"Декабрь"];
$month = $_monthsList[date("n")];
$last_month = $_monthsList[date("n",strtotime("-1 Months"))];
$last_sum = 0;
$status = "";
$confirm = "";

if (!empty($last_report)){
    $last_sum = $last_report['sum'];
    if($last_report->paid == 2){
        $status = "<div style='color: green;'>Выплачен</div>";
    } elseif ($last_report->paid == 1){
$status = "<div style='color: red;'>Не выплачен</div>";
$confirm = "<button class='btn btn-outline-danger btn-sm'
                                                style='margin: 5px'
                                                onclick='window.location.href = '{{route('paid.confirm', ['id'=>$last_report->room_id, 'month' => $last_report->month])}}';'>
                                            Выплатить
                                        </button>";
    }
}

    @endphp

    <section class="about-section text-center" id="about">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8">
                    @php
                            @endphp

                    <div style="margin-top: 50px">
                        <h1>Отчёт</h1>
                        <h3>{{$data->address}}</h3>

                    </div>

                    <div class="card text-center" style="margin: 15px">
                        <div class="card-header">
                            Выплата за - {{$last_month}}: {{$last_sum}} <i style="opacity: .8;"
                                                                           class="fa fa-rub"></i><small>{!! $status!!}</small>

                        </div>
                    </div>

                    @if(Auth::user()->isAdmin())
                        <h4>Админ инфо</h4>
                        @if (!empty($last_report))
                            @php
                                $paid = $last_report['sum'] * (65 / 100);
                            @endphp
                            <div class="card text-center" style="margin: 15px">
                                <div class="card-header">
                                    <h5>Выплаты за прошлый месяц {{$last_month}}</h5>
                                </div>
                                <div class="card-body">
                                    Всего ночей <b> {{$last_report['count_night']}}</b><br>
                                    К выплате: {{$paid}} <i style="opacity: .8;" class="fa fa-rub"></i>
                                </div>
                                <div class="card-footer text-muted">
                                    @if($last_report['paid'] == 1)
                                        <button class="btn btn-outline-danger btn-sm"
                                                style="margin: 5px"
                                                onclick="window.location.href = '{{route('paid', ['id'=>$last_report->room_id, 'month' => $last_report->month, 'paid'=>$paid])}}';">
                                            Выплатить
                                        </button>
                                    @else
                                        <div style="color: #2fa360">
                                            <b><h5>Выплачено</h5></b>
                                        </div>
                                        <button class="btn btn-outline-danger btn-sm"
                                                style="margin: 5px"
                                                onclick="window.location.href = '{{route('paid.cancel', ['id'=>$last_report['room_id'], 'month' => $last_report['month'], 'paid'=>$paid])}}';">
                                            Отменить
                                        </button>
                                    @endif
                                </div>
                                <button class="btn btn-primary btn-sm"
                                        style="margin: 5px"
                                        onclick="window.location.href = '{{route('reports')}}';">
                                    Назад
                                </button>
                            </div>
                        @else
                            <div class="card text-center" style="margin: 15px">
                                <div class="card-header">
                                    Данных для администратора не найдено
                                </div>
                            </div>
                        @endif
                    @endif
                    <div>
                        <h3>{{$month}}</h3>
                        Занятых ночей <b>{{$month}} - {{$count_night}} </b><br>
                        Сумма <b>{{$month}} - {{$sum}} <i style="opacity: .8;" class="fa fa-rub"></i></b><br>
                    </div>

                    <button class="btn btn-outline-success btn-sm"
                            onclick="window.location.href = '{{route('room.reports', ['id'=> $data['id']])}}';">Весь
                        период
                    </button>

                    <section>
                        <div class="container picker">
                            <div style="margin-top: 40px">
                                <center>
                                    <input id="input-id" name="date_book" type="text"
                                           class="form-control text-center"
                                           value="{{session('date_book') ?? '' }}" readonly="readonly"
                                           required>
                                </center>
                            </div>

                        </div>
                    </section>
                </div>
            </div>

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
        <script src="{{ asset('js/calendars/calendar3.js') }}" defer></script>

    @endpush

@endsection
