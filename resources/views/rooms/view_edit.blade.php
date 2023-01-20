@extends('layouts.app')
@section('content')

    <section class="about-section text-center" id="about">
        <div class="container-fluid px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8">
                    {{--                                                                Сообщения об ошибках--}}
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

                    <h3>Редактировать номер</h3><br>
                    <form id="form">
                        @csrf
                        <input type="hidden" name="id" value="{{$data['id']}}">
                        <div>
                            <label for="title"><b>Заголовок:</b></label>
                            <input name="title" type="text" value="{{$data['title'] ?? ''}}"
                                   class="form-control"
                                   placeholder="Заголовок" autocomplete="off" required>
                        </div>
                        <br>
                        <div>
                            <label for="address"><b>Адрес:</b></label>
                            <input name="address" type="text" value="{{$data['address'] ?? '' }}"
                                   class="form-control"
                                   placeholder="Адрес" autocomplete="off" required>
                        </div>

                        <div>
                            <label for="price"><b>Цена:</b></label>
                            <input name="price" type="text" value="{{$data['price'] ?? '' }}"
                                   class="form-control"
                                   onkeypress="return (event.charCode >= 48 && event.charCode <= 57 && /^\d{0,3}$/.test(this.value));"
                                   placeholder="Цена" autocomplete="off" required>
                        </div>
                        <br>
                        <div>

                            <label for="text_room"><b>Текст:</b></label><br>
                            <textarea class="form-control" placeholder="Введите текст" name="text_room" id="text"
                                      rows="5" cols="85"> {{$data['text_room'] ?? ''}}</textarea><br>
                        </div>
                        <br>
                        <div>
                            <label for="capacity"><b>Вместимость(человек):</b></label>
                            <input name="capacity" type="text" value="{{ $data['capacity'] ?? '' }}"
                                   class="form-control"
                                   onkeypress="return (event.charCode >= 48 && event.charCode <= 57 && /^\d{0,3}$/.test(this.value));"
                                   placeholder="Вместимость(человек)" autocomplete="off" required>
                        </div>
                        <br>
                        <div>
                            <label for="service"><b>Сервис:</b></label>
                            <input name="service" type="text" value="{{ $data['service'] ?? '' }}"
                                   class="form-control"
                                   placeholder="фен, утюг, телевизор, холодильник..." autocomplete="off" required>
                        </div>
                        <br>
                        <div>
                            <label for="video"><b>Видео:</b></label>
                            <input name="video" type="text" value="{{ $video ?? '' }}"
                                   class="form-control"
                                   placeholder="https://www.youtube.com/embed/WviGn7gjhdw" autocomplete="off">
                        </div>
                        <br>
                        <div>
                            <label for="coordinates"><b>Координаты:</b></label>
                            <input name="coordinates" type="text" value="{{ $data['coordinates'] ?? '' }}"
                                   class="form-control"
                                   placeholder="Координаты" autocomplete="off"
                                   required>
                        </div>
                        <br>
                        <div>
                            <label for="user_id"><b>Присвоить юзеру ID:</b></label>
                            <input name="user_id" type="number" value="{{$data['user_id'] ?? '' }}"
                                   class="form-control"
                                   placeholder="1" autocomplete="off" required>
                        </div>
                        <br>


                        <div id="file" class="upload"></div>
                        <br>
                        <div class="preview"></div>
                        <div class="files" id="files"></div>
                        <div class="file" id="file">
                            @if (!empty($images))
                                @foreach ($images as $item)
                                    <img class="img-thumbnail del" src="{{ asset("images/$item") }}/" alt=""
                                         data-file="{{$item}}">
                                @endforeach
                            @endif
                        </div>
                        <button class="btn btn-primary submit" id="submit" type="submit">Сохранить</button>
                        <img src="{{ asset('images/loader/Dual.gif') }}" width="100px" height="auto" alt=""
                             class="preloader-img"/>
                    </form>
                    <button class="btn btn-success" style="color: white; margin-top: 25px"
                            onclick="window.location.href = '{{route('num.id', ['id'=>$data->id])}}';">
                        Просмотр
                    </button>
                </div>
            </div>
        </div>
        <div class='form_radio'><input id='radio-"+ count +"' type='radio' name='radio' value='"+ value +"'><label for='radio-"+ count +"'>"+ value +"</label></div>

        @push('scripts')
            <script src="{{ asset('dropzone/dropzone.min.js') }}" defer></script>
            <link href="{{ asset('dropzone/dropzone.min.css') }}" rel="stylesheet">
            <script>
                var id = @json($data->id);
            </script>
            <script src="{{ asset('dropzone/drop.js') }}" defer></script>
        @endpush

    </section>
@endsection
