@extends('layouts.app')
@section('content')
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <section class="about-section text-center" id="about">
        <div class="container-fluid px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                @foreach ($data as $dat)
                    @php
                        $p = explode(',', $dat ['photo_room']);
                        $photo = $p[0];
                    @endphp
                    <div class="card" style="width: 18rem;">
                        <img src="{{ asset("images/$photo") }}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <div style="float: left; opacity: .6; ">
                                @for($i = 0; $i < $dat ['capacity']; $i++)
                                    <i class="fa fa-user"></i>
                                    {{--                        <p class="card-text"><?= $value['text_room']?></p>--}}
                                @endfor
                                @if(!empty($dat->price ))
                                    &nbsp;<b>От:{{$dat->price}}</b><i class="fa fa-rub"></i>
                                @endif
                            </div>


                        </div>
                        <div class="card-footer">
                            <button class="btn btn-outline-success"
                                    onclick="window.location.href = '{{route('num.id', ['id'=>$dat->id])}}';">Подробнее
                            </button>
                        </div>
                    </div>
            @endforeach
            <!---->
            </div>
        </div>

    </section>
@endsection
