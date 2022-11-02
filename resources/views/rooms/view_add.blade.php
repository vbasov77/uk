@extends('layouts.app')
@section('content')

    <section class="about-section text-center" id="about">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <br>
                    <h3>Создать номер</h3><br>
                    <form
                            id="form" action="{{route('room')}}" method="post">
                        @csrf
                        <div>
                            <label for="name_room"><b>Номер (название):</b></label>
                            <input name="name_room" type="text" value="{{ old('name_room') }}"
                                   class="form-control"
                                   placeholder="Номер (название)" autocomplete="off" required>
                        </div>
                        <br>
                        <div>
                            <label for="address"><b>Адрес:</b></label>
                            <input name="address" type="text" value="{{ old('address') }}"
                                   class="form-control"
                                   placeholder="Адрес" autocomplete="off" required>
                        </div>
                        <br>
                        <div>
                            <label for="price"><b>Цена:</b></label>
                            <input name="price" type="text" value="{{ old('price') }}"
                                   class="form-control"
                                   placeholder="Цена" autocomplete="off" required>
                        </div>
                        <br>
                        <div>
                            <label for="text_room"><b>Текст:</b></label>
                            <textarea placeholder="Введите текст..." name="text_room" cols="85" rows="5"
                                      value="{{ old('text_room') }}" class="form-control"></textarea>
                        </div>
                        <br>
                        <div>
                            <label for="capacity"><b>Вместимость(человек):</b></label>
                            <input name="capacity" type="text" value="{{ old('capacity') }}"
                                   class="form-control"
                                   placeholder="Вместимость(человек)" autocomplete="off" required>
                        </div>
                        <br>
                        <div>
                            <label for="service"><b>Сервис:</b></label>
                            <input name="service" type="text" value="{{ old('service') }}"
                                   class="form-control"
                                   placeholder="фен, утюг, телевизор, холодильник..." autocomplete="off" required>
                        </div>
                        <br>
                        <div>
                            <label for="video"><b>Видео YouTube:</b></label>
                            <input name="video" type="text" value="{{ old('video') }}"
                                   class="form-control"
                                   placeholder="https://www.youtube.com/watch?v=WviGn7gjhdw" autocomplete="off"
                                   required>
                        </div>
                        <br>
                        <div>
                            <label for="coordinates"><b>Координаты:</b></label>
                            <input name="coordinates" type="text" value="{{ old('coordinates') }}"
                                   class="form-control"
                                   placeholder="Координаты" autocomplete="off">
                        </div>
                        <br>
                        <div>
                            <label for="user_id"><b>Присвоить юзеру ID:</b></label>
                            <input name="user_id" type="number" value="{{ old('user_id') }}"
                                   class="form-control"
                                   placeholder="ID" autocomplete="off" required>
                        </div>
                        <br>
                        <button class="btn btn-primary submit" id="submit" type="submit">Перейти дальше</button>
                    </form>

                </div>
            </div>
        </div>
        @push('scripts')
            <script src="{{ asset('dropzone/drop.js') }}" defer></script>
        @endpush
    </section>
@endsection
