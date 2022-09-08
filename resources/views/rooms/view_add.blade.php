@extends('layouts.app')
@section('content')

    <section class="about-section text-center" id="about">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8">
                    <h3>Создать номер</h3><br>
                    <form
                            id="form" action="{{route('add.room')}}" method="post">
                        @csrf
                        <div>
                            <label for="name_room"><b>Номер (название):</b></label>
                            <input name="name_room" type="text" value="{{$_POST['name_room'] ?? ''}}"
                                   class="form-control"
                                   placeholder="№ 13" autocomplete="off" required>
                        </div>
                        <br>
                        <div>
                            <label for="address"><b>Адрес:</b></label>
                            <input name="address" type="text" value="{{$_POST['address'] ?? '' }}"
                                   class="form-control"
                                   placeholder="Боровая 11" autocomplete="off" required>
                        </div>
                        <br>
                        <div>
                            <label for="price"><b>Цена:</b></label>
                            <input name="price" type="text" value="{{$_POST['price'] ?? '' }}"
                                   class="form-control"
                                   placeholder="1500" autocomplete="off" required>
                        </div>
                        <br>
                        <div>
                            <label for="text_room"><b>Текст:</b></label>
                            <textarea placeholder="Введите текст" name="text_room" cols="85" rows="5"
                                      value="{{$_POST['text_room'] ?? '' }}" class="form-control"></textarea>
                        </div>
                        <br>
                        <div>
                            <label for="capacity"><b>Вместимость(человек):</b></label>
                            <input name="capacity" type="text" value="{{ $data['capacity'] ?? '' }}"
                                   class="form-control"
                                   placeholder="3" autocomplete="off" required>
                        </div>
                        <br>
                        <div>
                            <label for="service"><b>Сервис:</b></label>
                            <input name="service" type="text" value="{{$data['service'] ?? '' }}"
                                   class="form-control"
                                   placeholder="фен, утюг, телевизор, холодильник..." autocomplete="off" required>
                        </div>
                        <br>
                        <div>
                            <label for="video"><b>Видео YouTube:</b></label>
                            <input name="video" type="text" value="{{ $data['video'] ?? '' }}"
                                   class="form-control"
                                   placeholder="https://www.youtube.com/watch?v=WviGn7gjhdw" autocomplete="off"
                                   required>
                        </div>
                        <br>
                        <div>
                            <label for="coordinates"><b>Координаты:</b></label>
                            <input name="coordinates" type="text" value="{{ $data['coordinates'] ?? '' }}"
                                   class="form-control"
                                   placeholder="" autocomplete="off">
                        </div>
                        <br>
                        <div>
                            <label for="user_id"><b>Присвоить юзеру ID:</b></label>
                            <input name="user_id" type="number" value="{{$_POST['user_id'] ?? '' }}"
                                   class="form-control"
                                   placeholder="1" autocomplete="off" required>
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
