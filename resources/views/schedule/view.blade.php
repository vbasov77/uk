@extends('layouts.app')
@section('content')


    <center><h1 style="margin-top: 25px">Все объекты</h1><br></center>
    <section class="section text-center">
        <div class="container-fluid px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                @if(!empty(count($rooms)))
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Фото</th>
                            <th scope="col">Название</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($rooms as $room)
                            <tr>
                                <th scope="row"> {{$room->id }} </th>
                                <td>
                                    @if($room->path != null)
                                        <img src="{{ asset("images/$room->path") }}" style="width: 80px; height: auto"
                                             alt="...">
                                    @else
                                        <img src="{{ asset("images/no_image/no_image.jpg") }}"
                                             style="width: 80px; height: auto" alt="...">
                                    @endif
                                </td>
                                <td>{{$room->title}}</td>
                                <td>
                                    <button class="btn btn-success btn-sm" style="color: white; margin-top: 25px"
                                            onclick="window.location.href = '{{route('schedule.add', ['id'=>$room->id])}}';">
                                        Добавить
                                    </button>
                                    <button class="btn btn-info btn-sm" style="color: white; margin-top: 25px"
                                            onclick="window.location.href = '{{route('schedule.edit', ['id'=>$room->id])}}';">
                                        Изменить
                                    </button>
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    Не найдено...
                @endif
            </div>
        </div>


        <div id="calendar"></div>

    </section>








@endsection
