@extends('layouts.app')
@section('content')
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <section class="about-section text-center" id="about">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8">
                    <h1 style="margin-top: 40px">Мои объекты</h1>
                    <div class="card text-center" style="margin: 15px">
                        <div class="card-header">
                            <h6>Всего выплат: {{$payment}} <i style="opacity: .8;" class="fa fa-rub"></i></h6>
                        </div>
                    </div>
                    @if(!empty(count($data)))
                        @foreach($data as $value)
                            <div class="card text-center" style="margin: 15px">
                                <div class="card-header">
                                    ID {{$value->id}}
                                </div>
                                <div class="card-body">
                                    <h4>{{$value->address}}</h4>
                                </div>
                                <div class="card-footer text-muted">
                                    <button class="btn btn-outline-success btn-sm"
                                            style="margin: 5px"
                                            onclick="window.location.href = '{{route('my.obj', ['id'=> $value->id])}}';">
                                        Подробнее
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        Объектов не найдено...
                    @endif

                </div>
            </div>
        </div>
    </section>

@endsection
