@extends('layouts.app')
@section('content')

    {{--    <style>--}}
    {{--        input.tel {--}}
    {{--            margin-bottom: 15px;--}}
    {{--        }--}}
    {{--    </style>--}}

    <section class="about-section text-center" id="about">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8">
                    <h3>Заполните форму</h3>
                    <form action="{{route('order.info')}}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{$data['id']}}">
                        <input type="hidden" name="sum" value=" {{$sum}}">
                        <input type="hidden" value="{{implode(",", $date_view)}}" name="date_view">
                        <input type="hidden"
                               value="Итого: <b>{{$sum_night}}</b> сут./<b>{{$sum}} руб.</b>"
                               name="summ">
                        <div class="border_none">
                            <label for="date_book"><b>Выбранные даты:</b></label><br>
                            <input class="form-control" value="{{$data['date_book']}}"
                                   readonly="readonly" type="text"
                                   name="date_book"
                                   method="post"><br>
                        </div>
                        <br>
                        <div style="background: #e9ecef; padding: 15px;">
                            @foreach($date_view as $dat)
                                {{$dat}} руб.<br>
                            @endforeach
                            Итого: <b>{{$sum_night}}</b> сут./<b>{{$sum}}руб.</b>
                        </div>
                        <br>

                        <div>
                            <label for="phone_user"><b>Телефон:</b></label><br>
                            <input name="phone_user" type="text" class="tel form-control"
                                   value="{{$_POST['phone_user'] ?? ''}}" placeholder="+7(000) 000-0000" required>
                        </div>
                        <br>
                        <div>
                            <label for="email_user"><b>Email:</b></label>
                            <input name="email_user" type="email" class="form-control"
                                   onKeypress="javascript:if(event.keyCode == 32)event.returnValue = false;"
                                   value="{{$_POST['email_user'] ?? '' }}" placeholder="Email" required>
                        </div>
                        <br>
                        @for ($i = 1; $i <= $data['guests']; $i++)
                            <h3> {{$i}} гость</h3>
                            <div>
                                <label for="ФИО"><b>ФИО:</b></label>
                                <input name="name_user[]" id="name_user" type="text" class="form-control"
                                       value="{{$_POST['name_user'] ?? ''}}" placeholder="ФИО" required>
                            </div>
                            <div>
                                <label for="age"><b>Возраст:</b></label>
                                <input name="age[]" type="text" class="form-control"
                                       value="{{ $_POST['age'] ?? ''}}" placeholder="Полных лет" required>
                            </div>
                            <div>
                                <label for="nationality"><b>Район:</b></label>
                                <input name="nationality[]" type="text" class="form-control"
                                       placeholder="Город, область жительства" required>
                            </div>
                            <br>
                        @endfor
                        <br>
                        <div>
                            <input class="btn btn-outline-primary" type="submit" value="Продолжить">
                        </div>
                        <br>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </section>
    @push('scripts')
        <script src="{{ asset('js/masks/mask.js') }}" defer></script>
        <script src="{{ asset('js/email/email.js') }}" defer></script>
    @endpush
@endsection
