@extends('layouts.app')
@section('content')
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <div class="container">
        <center>
            <h1>База очищена</h1>

            <font color='#99b1c6'><i style="font-size: 150px" class="fa fa-smile-o" aria-hidden="true"></i></font>
            <br>
            <br>

            <h2 style="margin-top: 25px">Очищено {{ $count }} записей...</h2>
        </center>



    </div>


@endsection
