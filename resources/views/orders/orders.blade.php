@extends('layouts.app')
@section('content')

    <section class="about-section text-center" id="about">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center text-center">
                <div class="col-lg-8">
                    <h3 style="margin-top: 25px">Бронь</h3>
                    <br>
                    @if (!empty($data))
                        @for($i = 0; $i < count($data); $i++)
                            @php
                                $pay = explode(';', $data [$i]->info_pay);
                                $phone = preg_replace("/[^0-9]/", '', $data [$i]->phone_user);
                                $archive  =
                                    \App\Models\Archive::where('email_user', $data [$i]->email_user)
                                        ->orWhere('phone_user', $data [$i]->phone_user)
                                        ->get(['otz', 'id']);
                            @endphp

                            <div class="card text-center" style="margin: 15px">
                                <div class="card-header">
                                    Даты: {{$data [$i]->no_in }}- {{$data [$i]->no_out}}<br>
                                </div>
                                <div class="card-body">
                                    Имя: {{$data[$i]->name_user}}<br>
                                    Телефон: <a href='tel:+{{$phone}}'>{{$data [$i]->phone_user}}</a><br>
                                    Сумма: {{$data [$i]->summ}}<br>
                                    @if($pay[0] == 0)
                                        Не оплачен
                                    @else
                                        Оплачен
                                    @endif
                                    @if(!empty(count($archive)))
                                        <br><small><i>Имеются отзывы:
                                                {{$num = 1}}
                                                @foreach($archive as $value)
                                                    <div style="color: red">
                                                        {{$num++ . ". " . $value ['otz']}}
                                                        <button class="btn btn-outline-link btn-sm"
                                                                style="margin: 5px"
                                                                onclick="window.location.href = '{{route('view.archive', ['id'=>$data[$i]->id])}}';">
                                                            <small>Подробнее</small>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </i></small>
                                    @endif

                                </div>
                                <div class="card-footer text-muted">
                                    @if($data [$i]->confirmed == 0)<br>
                                    <button class="btn btn-outline-success btn-sm"
                                            style="margin: 5px"
                                            onclick="window.location.href = '{{route('order.confirm', ['id'=> $data[$i]->id])}}';">
                                        Подтвердить
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm"
                                            style="margin: 5px"
                                            onclick="window.location.href = '{{route('order.reject', ['id'=> $data[$i]->id])}}';">
                                        Отклонить
                                    </button>
                                    <br>
                                    @endif
                                    <button class="btn btn-outline-warning btn-sm"
                                            style="margin: 5px"
                                            onclick="window.location.href = '{{route('order.verification', ['id'=> $data[$i]->id])}}';">
                                        Подробнее
                                    </button>
                                    <a onClick="return confirm('Подтвердите удаление!')"
                                       href='{{route('order.delete', ['id'=> $data[$i]->id])}}' type='button'
                                       class='btn btn-outline-danger btn-sm' style="margin: 5px">Удалить</a>
                                    <br>
                                    <br>
                                    <form action="{{route('in.archive')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$data[$i]-> id}}"/>
                                        <i>Отзыв администратора</i><br>
                                        <input type="text" name="otz" id="otz" class="form-control"
                                               placeholder="Отзыв администратора"/>
                                        <br>
                                        <div>
                                            <input id="submit" class="btn btn-outline-dark btn-sm" type="submit"
                                                   value="В архив"/>

                                        </div>
                                    </form>

                                </div>

                            </div>
                        @endfor
                    @else
                        Заказов не найдено((
                    @endif
                </div>
            </div>
        </div>
    </section>
    @push('scripts')
        <script src="{{ asset('js/otz/otz.js') }}" defer></script>
    @endpush

@endsection
