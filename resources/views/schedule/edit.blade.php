@extends('layouts.app')
@section('content')
    <section class="about-section text-center" id="about">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8">
                    <h2>{{$name_room}}</h2>
                    <form action="{{route('schedule.edit')}}" method="post">
                        @csrf
                        <input type="hidden" name="room" value="{{$id}}">
                        <h3> Изменить расписание </h3>
                        <br>
                        <h3> Выбор дат </h3><br>
                        <div class="size">
                            <label for="date_book"><b>Выберете даты, которые нужно изменить:</b></label>
                            <input  id="input-id" name="date_book" type="text" class="form-control"
                                   placeholder="Нажмите для выбора даты" autocomplete="off" required>
                        </div>

                        <br>
                        <div>
                            <input class="btn btn-outline-primary" type="submit" value="Изменить">
                        </div>
                    </form>
                </div>
            </div>
        </div>


        @push('scripts')
            <script src="{{ asset('js/fecha.min.js') }}" defer></script>
            <link href="{{ asset('css/hotel-datepicker.css') }}" rel="stylesheet">
            <script src="{{ asset('js/hotel-datepicker.min.js') }}" defer></script>
            <script src="{{ asset('js/calendars/schedule_cal2.js') }}" defer></script>
        @endpush

    </section>








@endsection
