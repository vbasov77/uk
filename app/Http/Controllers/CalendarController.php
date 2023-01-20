<?php

namespace App\Http\Controllers;

use App\Mail\NewBooking;
use App\Mail\SendBooking;
use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;


class CalendarController extends Controller
{
    public function addBooking(Request $request)
    {

        //Проверка на занятость дат
        // Если за время бронирования, даты уже кто-то занял, или исключить повторного бронирования,
        // сообщаем юзеру о недопустимости
        $check = explode(',', $request->date_view); // Получаем массив выбранных юзером дат из строки инфы
        for ($i = 0; $i < count($check) - 1; $i++) {
            // Разбиваем массив инфы типа 0 => 31.10.2022/1300
            // на массив только с датами 0 => 31.10.2022
            $it = explode('/', $check[$i]);
            $user_dates[] = $it[0];
        }

        // Получим массив всех занятых дат объекта по id
        $all_dates = Booking::where('room', $request->id)->get('date_book');

        //Если имеются забронированные даты объекта, то сравниваем эти даты с выбранными датами юзера
        if (!empty(count($all_dates))) {
            foreach ($all_dates as $value) {
                // Формируем многомерный массив всех занятых дат
                $tab = explode(',', $value->date_book);
                $array_dates[] = $tab;
            }

            // Переобразуем многомерный массив всех занятых дат объекта в численный
            $array_dates = Arr::collapse($array_dates);

            // Если одна из дат юзера присутствует в массиве всех занятых дат объекта, сообщаем о недопустимости
            foreach ($user_dates as $value) {
                if (in_array($value, $array_dates)) {
                    return redirect()->action('CalendarController@comeErrorBlade');
                }

            }

        }
        // Конец проверки

        // Вытаскиваем имя юзера из строки
        $user_info = implode(';', $request->more_book);
        $user = explode(",", preg_replace('/\s+?\'\s+?/', '\'', $user_info));
        $name_user = $user[0];
        // Вытаскиваем Email юзера
        $email = preg_replace("/\s+/", "", $request->email_user);

        // Проверяем не зарегистрирован ли клиент на сайте и если нет,
        // то регистрируем его и отправляем сообщение на почту о регистрации и создании личного кабинета,
        // где он сможет проследить статус бронирования...
        CheckController::checkEmailEndRegister($name_user, $email);

        $date = $request->date_book; // Достали данные календаря выбранных дат клиентом (02.11.2022 - 05.11.2022)
        $date = preg_replace("/\s+/", "", $date); // удалили пробелы
        $date_array = explode("-", $date); // преобразовали в массив

        // Изменение данных в БД Отчёты. Изменяем сумму
        $condition = 1;                                            // 1 - прибавить, 2 - вычесть
        DateController::setCountNightObj($date_array, $request->id, $request->sum, $condition);

        // Формируем массив диапазона [0] - старт, [1] - завершения
        $start_data = $date_array[0];
        $end_data = $date_array[1];
        $date_b = DateController::getDates($start_data, $end_data);// Получили массив дат из диапазона
        $date_book = implode(',', $date_b);// Переводим в строку массив дат для добавления в BD
        // Добавляем бронирование в БД
        $info_users = implode('&', $request->more_book);
        $id = Booking::insertGetId([
                'room' => $request->id,
                'name_user' => $name_user,
                'phone_user' => $request->phone_user,
                'email_user' => $request->email_user,
                'date_book' => $date_book,
                'no_in' => $start_data,
                'no_out' => $end_data,
                'more_book' => $request->date_view,
                'user_info' => $info_users,
                'summ' => $request->sum,
            ]
        );
        // Формируем данные для писем клиенту и админу
        $data = [
            'in' => $start_data,
            'out' => $end_data,
            'name_user' => $name_user,
            'id' => $id,
            'url' => request()->root(),

        ];

        $subject = 'Бронирование дат'; // Заголовок письма юзеру
        Mail::to($email)->send(new SendBooking($subject, $data));// Отправка письма юзеру
        $sub3 = 'Новое бронирование'; // Заголовок письма админу
        $email_admin = '0120912@mail.ru'; // Емаил админа
        Mail::to($email_admin)->send(new NewBooking($sub3, $data));// Отправка письма админу
        $mess = MessageController::booking($email); // Сообщение, что бронирование прошло успешно.
        // Редирект на страницу с благодарностью
        return redirect()->action('DankeController@view', ['mess' => $mess]);
    }


    public function verification(Request $request)
    {
        // Валидация введённых данных клиентом
        $request->validate([
            'phone_user' => 'required|max:18',
            'email_user' => 'required|max:100',
            'age' => 'required|max:3',
        ]);

        // Формируем информационный массив из строки ($request->date_view) 03.11.2022/1300,04.11.2022/1300...
        $date_array = explode(',', $request->date_view);// 0 => "03.11.2022/1300"  1 => "04.11.2022/1300"
        $date_array[] = $request->summ;

        // Формируем массив информации всех гостей: 0 => "Иван Иванов, 45, Москва", 1 => "Галина Иванова, 43, Москва"
        $guests = [];
        for ($li = 0; $li < count($request->name_user); $li++) {
            $guests[] = $request->name_user [$li] . ", " . $request->age [$li] . ", " . $request->from [$li];
        }

        return view('verifications.verification_booking')->with(['date_view' => $date_array, 'more_book' => $guests, 'id' => $request->id, 'sum' => $request->sum]);
    }

    public function setInfo(Request $request)
    {

        $request->validate([
            'date_book' => 'required'
        ]);

        //Определение и подсчёт стоимости за каждый выбранный день
        $array_rooms = Schedule::where('room', $request->id)->get(); // Достали инфу стоимости всех дат по id объекта из BD

        //Формируем индексный массив дат из БД
        $array_date = [];
        foreach ($array_rooms as $value) {
            $array_date[] = $value->date_book;
        }

        // Формируем массив диапазона выбранных дат клиентом для определения стоимости
        $date_str = preg_replace("/\s+/", "", $request->date_book);// удалили пробелы
        $date_arr = explode("-", $date_str); // Разбили на массив диапазона
        $arr_date = DateController::getDates($date_arr[0], $date_arr[1]); // Получили все даты из диапазона
        $arr_date[] = $date_arr[1];

        // Определение стоимости по датам
        $sum_night = count($arr_date); // Количество ночей
        $date_view = [];
        foreach ($arr_date as $item) {
            // проверка есть ли в массиве, если да, то дастаём стоимости даты из массива $array_rooms
            // Если выбранной даты нет в массиве, то отправляем сообщение, что админом не заполнена одна из дат.
            if (!empty(in_array($item, $array_date))) {
                foreach ($array_rooms as $room) {
                    if ($room->date_book == $item) {
                        $cumm_cost = $room->cost; // Стоимость за ночь
                        $date_view[] = $item . "/" . $cumm_cost; // Строка дата/стоимость для вывода информации пользователю
                        $cost[] = $cumm_cost;
                    }
                }
            } else {
                return view('sorry.sorry');
            }
        }
        $sum = array_sum($cost);
        $data = $_POST;
        return view('orders.order_info', ['data' => $data, 'date_view' => $date_view, 'sum' => $sum, 'sum_night' => $sum_night]);

    }

    public function comeErrorBlade()
    {
        return view('errors.error_book');
    }


}
