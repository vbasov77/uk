@extends('layouts.app')
@section('content')

    <section class="about-section text-center" id="about">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8">

                    <h3>Редактировать главную страницу</h3>

                    <form action="{{route('front.edit')}}" onsubmit="return Validate(this);"
                          enctype="multipart/form-data" method="post">
                        @csrf
                        <label><b>Заголовок</b></label><br>
                        <div>
                            <input class="form-control" value="{{$data[0] ?? '' }}" type="text"
                                   name="data[]"
                                   method="post" required><br>
                        </div>

                        <div>
                            <label><b>Цвет заголовка</b></label><br>
                            <input class="form-control" value="{{ $data[1] ?? '' }}" type="text"
                                   name="data[]"
                                   method="post" required><br>
                        </div>
                        <div>
                            <label><b>Шлифт заголовка</b></label><br>
                            <input class="form-control" value="{{$data[2] ?? '' }}" type="text"
                                   name="data[]"
                                   method="post" required><br>
                        </div>

                        <div>
                            <label><b>Фото фона</b></label><br>
                            <input class="form-control" value="{{ $data[3] ?? '' }}" type="text"
                                   name="data[]" readonly="readonly"
                                   method="post" required><br>
                        </div>

                        <div>
                            <label><b>Изменить фото фона</b></label><br>
                            <input class="form-control" type="file"
                                   name="file"
                                   method="post"><br>
                        </div>
                        {{--                        <div>--}}
                        {{--                            <label><b>Карта:</b></label><br>--}}
                        {{--                            <input class="form-control" value="<?= (string) $data[4] ?? '' ?>" type="text"--}}
                        {{--                                   name="data[]"--}}
                        {{--                                   method="post" required><br>--}}
                        {{--                        </div>--}}
                        <div>
                            <input class="btn btn-outline-success" type="submit" value="Сохранить">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @push('scripts')
            <script src="{{ asset('js/checks/check_file.js') }}" defer></script>
        @endpush
    </section>

@endsection
