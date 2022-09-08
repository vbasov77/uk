@extends('layouts.app')
@section('content')
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <section class="about-section text-center" id="about">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8">
                    <h1 style="margin-top: 40px">Весь период</h1>
                    @if(!empty(count($reports)))
                        <table style="text-align: left" class="table">
                            <thead>
                            <tr>
                                <th scope="col">Месяц</th>
                                <th scope="col">Ночей</th>
                                <th scope="col">Выплачено</th>
                                <th scope="col">Статус</th>


                            </tr>
                            </thead>

                            <tbody>
                            @foreach($reports as $report)
                                @php
                                    if($report->paid == 1){
                                      $paid = "<div style='color: green;'>Выплачен</div>";
                                    } else{
        $paid = "<div style='color: red;'>Не выплачен</div>";
                                    }
                                @endphp
                                <tr>
                                    <th scope="row"> {{$report->month}} </th>
                                    <th scope="row"> {{$report->count_night}} </th>
                                    <th scope="row"> {{$report->sum}} </th>
                                    <th scope="row"> {!! $paid !!} </th>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        Отчётов пока не найдено...
                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection
