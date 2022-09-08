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

class OrdersController extends Controller
{
    public function view()
    {
        if (Auth::check()) {
            $data = DateController::getBookingDates();
            $arri = DateController::inOrder(); //Формируем даты по порядку
            return view('orders.orders')->with(['data' => $arri, 'data2' => $data]);
        } else {
            return redirect()->route('login');
        }
    }

    public function viewForEdit($id)
    {
        $order = DbController::GetBookingOrderId($id);
        return view('/orders.order_edit')->with(['order' => $order]);
    }

    public function edit()
    {
        DbController::updateOrder($_POST);
        return redirect()->action('OrdersController@view');
    }

    public function delete(int $id)
    {
        $result = Booking::where('id', $id)->get();
        $date[]= $result[0]->no_in;
        $date[]= $result[0]->no_out;
        $condition = 2;
        DateController::setCountNightObj($date, $result[0]->room, $result[0] ->summ, $condition);
        Booking::where('id', $id)->delete();
        return redirect()->action('OrdersController@view');
    }

    public function confirm(int $id)
    {
        $booking = Booking::where('id', $id)->get();;
        $code = $booking[0]->code_book;
        Booking::where('code_book', $code)->update(['confirmed' => 1]);
        $result = Booking::where('id', $id)->get();
        $data = [
            'name_user' => $result[0]->name_user,
            'in' => $result[0]->no_in,
            'out' => $result[0]->no_out,
            'sum' => $result[0]->summ
        ];
        $subject = 'Подтверждение бронирования';
        $toEmail = $result[0]->email_user;
        Mail::to($toEmail)->send(new ConfirmOrder($subject, $data));
        return redirect()->action('OrdersController@view');
    }

    public function reject(int $id)
    {
        $result = Booking::where('id', $id)->get();
        $date[]= $result[0]->no_in;
        $date[]= $result[0]->no_out;
        $condition = 2;
        DateController::setCountNightObj($date, $result[0]->room, $result[0] ->summ, $condition);
        $data = [
            'name_user' => $result [0]->name_user,
            'in' => $result [0]->no_in,
            'out' => $result [0]->no_out,
            'sum' => $result [0]->summ
        ];
        $subject = 'Бронирование не подтверждено';
        $toEmail = preg_replace("/\s+/", "", $result [0]['email_user']);
        Mail::to($toEmail)->send(new RejectOrder($subject, $data));
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
        Archive::insert($data);
        Booking::where('id', $id)->delete();
        return redirect()->action('OrdersController@view');
    }


}
