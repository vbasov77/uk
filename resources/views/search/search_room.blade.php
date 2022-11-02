@extends('layouts.app')
@section('content')
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <section class="about-section text-center" id="about">
        <div class="container-fluid px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                @foreach ($data as $value)

                    <div class="card" style="width: 18rem;">
                        @if($value->path != null)
                            <img src="{{ asset("images/$value->path") }}" class="card-img-top"
                                 alt="...">
                        @else
                            <img src="{{ asset("images/no_image/no_image.jpg") }}" class="card-img-top" alt="...">
                        @endif
                        <div class="card-body">
                            <div style="float: left; opacity: .6; ">
                                @for($i = 0; $i < $value ['capacity']; $i++)
                                    <i class="fa fa-user"></i>
                                @endfor
                                @if(!empty($value->price ))
                                    &nbsp;<b>От:{{$value->price}}</b><i class="fa fa-rub"></i>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-outline-success"
                                    onclick="window.location.href = '{{route('num.id', ['id'=>$value->id])}}';">Подробнее
                            </button>
                        </div>
                    </div>
            @endforeach
            </div>
        </div>

    </section>
@endsection
