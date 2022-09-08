@extends('layouts.app')
@section('content')
    @php
        $_monthsList = [
    "1"=>"Январь","2"=>"Февраль","3"=>"Март",
    "4"=>"Апрель","5"=>"Май", "6"=>"Июнь",
    "7"=>"Июль","8"=>"Август","9"=>"Сентябрь",
    "10"=>"Октябрь","11"=>"Ноябрь","12"=>"Декабрь"];
    $month = $_monthsList[date("n")];
    $last_month = $_monthsList[date("n",strtotime("-1 Months"))];
    @endphp

    <section class="section">
        <div class="container-fluid px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">

                <h1 style="margin-top: 40px">Отчёт за {{$month}}</h1>
                @php
                    $total = 0;
$all_night = 0;
$all_sum = 0;
$all_to_issue = 0;
                @endphp
                <table style="text-align: left" class="table">
                    <thead>
                    <tr>
                        <th scope="col">Адрес</th>
                        <th scope="col">Ночей</th>
                        <th scope="col">Приход</th>
                        <th scope="col">Расход</th>
                        <th scope="col">Итог</th>
                        <th scope="col">{{$last_month}}</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rooms as $room)
                        @php
                            $report = \App\Models\Reports::where('room_id', $room-> id)->where('month', date('m.Y'))->first();
                            $last_report = \App\Models\Reports::where('room_id', $room-> id)->where('month', date('m.Y',strtotime("-1 Months")))->first();
                            $to_issue = $report['sum'] * (65 / 100);
$zp = $report['sum'] * (35 / 100)

                        @endphp
                        <tr>

                            <th scope="row"> {{$room-> address}} </th>
                            <th scope="row"> {{$report['count_night']}} </th>
                            <th scope="row"> {{$report['sum']}} </th>
                            <th scope="row"> {{$to_issue}} </th>
                            <th scope="row"> {{$zp}} </th>
                            @if($last_report['paid'] == null)
                                <th scope="row"><small>Не существует</small></th>
                            @elseif($last_report['paid'] == 2)
                                <th style="color: green" scope="row"> Выплачен</th>
                            @else
                                <th style="color: red" scope="row"> Не выплачен</th>
                            @endif
                            <td>
                                <button class="btn btn-success btn-sm" style="color: white; margin-top: 25px"
                                        onclick="window.location.href = '{{route('my.obj', ['id' => $room->id])}}';">
                                    Подробнее
                                </button>
                            </td>
                        </tr>
                        @php

                            $all_night = $all_night + $report['count_night'];
                            $all_sum = $all_sum + $report['sum'];
                            $all_to_issue = $all_to_issue + $to_issue;
                            $total = $total + $zp;

                        @endphp
                    @endforeach
                    </tbody>
                    <br>
                </table>
                <h4>ИТОГО:</h4>
                <br>
                <table style="text-align: left" class="table">
                    <thead>
                    <tr>
                        <th scope="col">Ночей</th>
                        <th scope="col">Приход</th>
                        <th scope="col">Расход</th>
                        <th scope="col">Итог</th>

                    </tr>
                    </thead>
                    <tbody>

                    <tr>
                        <th scope="row"> {{$all_night}} </th>
                        <th scope="row"> {{$all_sum}} </th>
                        <th scope="row"> {{$all_to_issue}} </th>
                        <th scope="row"> {{$total}} </th>
                        <td>
                            <button class="btn btn-success btn-sm" style="color: white; margin-top: 25px"
                                    onclick='window.location.href = "#";'>
                                Подробнее
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </section>

@endsection
