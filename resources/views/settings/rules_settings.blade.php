@extends('layouts.app')
@section('content')


    <section class="about-section text-center" id="about">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8">
                    <form action="{{route('rules_edit')}}" method="post">
                        @csrf
                        <h3>Редактировать правила календаря</h3>
                        <div>
                            <br>
                            <label for="startDay"><b>С какого дня можно бронировать</b></label><br>
                            <input type="radio" name="rules[]" value="1"
                                    @php
                                        if (!empty( $rules [0]) and $rules [0] === '1') {
                                            echo 'checked';
                                        }
                                    @endphp> Сегодня
                            <input type="radio" name="rules[]" value="2"
                                    @php
                                        if (!empty($rules [0]) and $rules [0] === '2') {
                                            echo 'checked';
                                        }
                                    @endphp> Завтра

                        </div>
                        <br>
                        <div>
                            <label for="rules[]"><b>Минимальное количество дней</b></label><br>
                            <input name="rules[]" type="text" value="<?= $rules [1] ?? '' ?>"
                                   class="form-control" style="text-align: center"
                                   placeholder="Минимум дней"
                                   onkeypress="return (event.charCode >= 48 && event.charCode <= 57 && /^\d{0,3}$/.test(this.value));"
                                   autocomplete="off" required>
                        </div>
                        <br>
                        <div>
                            <label for="rules[]"><b>Максимальное количество дней</b></label><br>
                            <input name="rules[]" type="text" value="<?= $rules [2] ?? '' ?>"
                                   class="form-control" style="text-align: center"
                                   placeholder="Максимум дней"
                                   onkeypress="return (event.charCode >= 48 && event.charCode <= 57 && /^\d{0,3}$/.test(this.value));"
                                   autocomplete="off" required>
                        </div>

                        <div>
                            <input class="btn btn-outline-success" type="submit" value="Сохранить">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    @push('scripts')
        <script src="{{ asset('js/checks/check_file.js') }}" defer></script>
    @endpush
@endsection
