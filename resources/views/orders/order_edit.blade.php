@extends('layouts.app')
@section('content')

    <style>
        input.tel {
            margin-bottom: 15px;
        }
    </style>

    <section class="about-section text-center" id="about">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8">
                    <h3>Редактировать заказ</h3>

                    <form action="/order_edit" method="post">

                        @csrf
                        <div class="border_none">
                            <label for="id"><b>ID заказа</b></label><br>

                            <input class="form-control" value="<?= $order[0]['id'] ?>" readonly="readonly" type="text"
                                   name="id"
                                   method="post"><br>

                        </div>


                        <div class="border_none">
                            <label for="date_book"><b>Выбранные даты:</b></label><br>

                            <input class="form-control" value="<?= $order[0]['no_in'] ?> - <?= $order[0]['no_out'] ?>"
                                   readonly="readonly" type="text"
                                   name="date_book"
                                   method="post"><br>

                        </div>

                        <div class="border_none">
                            <label for="name_user"><b>ФИО:</b></label><br>

                            <input class="form-control" value="<?= $order[0]['name_user'] ?>" type="text"
                                   name="name_user"
                                   method="post"><br>

                        </div>

                        <div class="border_none">
                            <label for="phone_user"><b>Телефон:</b></label><br>

                            <input class="tel" value="<?= $order[0]['phone_user'] ?>" type="text"
                                   name="phone_user"
                                   method="post"><br>

                        </div>

                        <div class="border_none">
                            <label for="email_user"><b>Email:</b></label><br>

                            <input class="form-control" value="<?= $order[0]['email_user'] ?>" type="text"
                                   name="email_user"
                                   method="post"><br>

                        </div>

                        <div class="border_none">
                            <label for="nationality"><b>Гражданство:</b></label><br>

                            <input class="form-control" value="<?= $order[0]['nationality'] ?>" type="text"
                                   name="nationality"
                                   method="post"><br>

                        </div>
                        <br>


                        <div class="border_none">
                            <label for="summ"><b>Сумма:</b></label><br>

                            <input class="form-control" value="<?= $order[0]['summ'] ?>" type="text"
                                   name="summ"
                                   method="post"><br>

                        </div>

                        <div>
                            <input class="btn btn-outline-primary" type="submit" value="Редактировать">
                        </div>
                        <br>
                        <br>

                    </form>
                </div>
            </div>

        </div>
    </section>
    <script src="{{ asset('js/mask.js') }}" defer></script>
@endsection
