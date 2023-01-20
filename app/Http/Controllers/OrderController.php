<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmOrder;
use App\Mail\RejectOrder;
use App\Mail\SendBooking;
use App\Models\Archive;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function view()
    {
        // Формируем данные для отображения всех бронирований по роуту orders
        if (Auth::check() && Auth::user()->isAdmin()) {
            $data = DateController::getBookingDates(); // Получили даты
            $dates= DateController::inOrder(); //Формируем даты по порядку
            return view('orders.orders')->with(['data' => $dates, 'data_calendar' => $data]);
        } else {
            return redirect()->route('login');
        }
    }


    public function delete(int $id)
    {
        // Удаление бронирования и изменение записи в отчётах
        $result = Booking::where('id', $id)->get();// остаём инфу брони для изменения в отчётах
        $date[]= $result[0]->no_in; // Дата въезда
        $date[]= $result[0]->no_out;// Дата выезда
        $condition = 2; //  1- добавление, 2 - удаление
        DateController::setCountNightObj($date, $result[0]->room, $result[0] ->summ, $condition);
        Booking::where('id', $id)->delete();
        return redirect()->action('OrderController@view');
    }

    public function confirm(Request $request)
    {

        // Подтверждение бронирования админом.
        // Изначально поле confirmed в БД имеет значение - 0(не подтверждено). Изменяем на 1(подтверждено)
        // Отправляем клиенту письмо, что бронирование подтверждено, можно вносить предоплату
        $booking = Booking::where('id', $request->id)->get(); // Получили бронирование по id для инфы в письмо
        Booking::where('id', $request->id)->update(['confirmed' => 1]);
        $result = Booking::where('id', $request->id)->get();
        // Формируем данные для письма
        $data = [
            'name_user' => $result[0]->name_user,
            'in' => $result[0]->no_in,
            'out' => $result[0]->no_out,
            'sum' => $result[0]->summ
        ];
        $subject = 'Подтверждение бронирования'; // Заголовок
        $to_email = $result[0]->email_user;
        Mail::to($to_email)->send(new ConfirmOrder($subject, $data));// Отправили письмо
        return redirect()->action('OrderController@view');
    }

    public function reject(int $id)
    {
        // Отклонение бронирования администратором и отправка письма клиенту, что бронирование отклонено
        // А также изменение данных в отчётах
        // Перенос бронирования в архив с сообщением "Отклонено администратором"

        $result = Booking::where('id', $id)->get(); // Получили данные бронирования
        // Создаём массив диапазона дат для отчётов
        $date[]= $result[0]->no_in;
        $date[]= $result[0]->no_out;
        $condition = 2; // Данные для вычета (1 - сложение, 2 - вычетание)
        DateController::setCountNightObj($date, $result[0]->room, $result[0] ->summ, $condition); //Изменение в отчётах
        // Формируем данные для письма
        $data = [
            'name_user' => $result [0]->name_user,
            'in' => $result [0]->no_in,
            'out' => $result [0]->no_out,
            'sum' => $result [0]->summ
        ];
        $subject = 'Бронирование не подтверждено'; // Заголовок письма
        $to_email = preg_replace("/\s+/", "", $result [0]['email_user']);
        Mail::to($to_email)->send(new RejectOrder($subject, $data)); // Послали письмо
        // Формируем данные для архива
        $data = [
            'name_user' => $result [0]->name_user,
            'phone_user' => $result [0]->phone_user,
            'email_user' => $result [0]->email_user,
            'no_in' => $result [0]->no_in,
            'no_out' => $result[0]->no_out,
            'user_info' => $result[0]->user_info,
            'summ' => $result [0]->summ,
            'pay' => $result[0]->pay,
            'info_pay' => $result[0]->info_pay,
            'confirmed' => $result[0]->confirmed,
            'otz' => "Отклонено администратором"
        ];
        Archive::insert($data); // Перенесли данные бронирования в архив
        Booking::where('id', $id)->delete(); // Удалили бронирование
        return redirect()->action('OrderController@view');
    }


}
