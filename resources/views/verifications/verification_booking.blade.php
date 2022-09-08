@extends('layouts.app')
@section('content')
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center text-center">
            <div class="col-lg-8">
                <form action="{{route('add.booking')}}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{$id}}">
                    <input type="hidden" name="sum" value="{{$sum}}">
                    <input type="hidden" value="{{implode(",", $date_view)}}" name="date_view">
                    <h3>Проверьте данные:</h3><br>
                    <div class="border_none">
                        <label for="date_book"><b>Выбранные даты:</b></label><br>

                        <input class="form-control" value="{{$_POST['date_book']}}" readonly="readonly" type="text"
                               name="date_book"
                               method="post"><br>
                    </div>
                    @foreach ($date_view as $value)
                    <div>
                        {!!$value!!} <br>
                    </div>
                    @endforeach
                    <br>
                    <div class="border_none">
                        <label for="phone_user"><b>Телефон:</b></label><br>

                        <input class="form-control" value="{{$_POST['phone_user']}}" readonly="readonly" type="text"
                               name="phone_user"
                               method="post"><br>
                    </div>
                    <div class="border_none">
                        <label for="email_user"><b>Email:</b></label><br>
                        <input class="form-control" value="{{$_POST['email_user']}}" readonly="readonly" type="text"
                               name="email_user"
                               method="post"><br>
                    </div>
                    <b>Гости:</b>
                    @foreach ($more_book as $item)
                    <div class="border_none">
                        <input class="form-control" value="{{$item}}" readonly="readonly" type="text"
                               name="more_book[]"
                               method="post">
                        <br>
                    </div>
                    @endforeach
                    <br>
                    <div>
                        <input class="btn btn-outline-primary" type="submit" value="Подтвердить">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
