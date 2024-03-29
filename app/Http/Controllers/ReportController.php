<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Report;
use App\Models\Room;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function view()
    {
        $rooms = Room::all();
        return view('reports.view', ['rooms' => $rooms]);
    }

    public function roomReports(Request $request)
    {
        // Формирование отчёта за весь период объекта
        $reports = false;
        $result = Report::where('room_id', $request->id)->get();
        // Формируем массив по порядку
        if (!empty(count($result))) {
            for ($i = 0; $i < count($result); $i++) {
                $array[] = strtotime("01." . $result [$i]->month);
            }
            sort($array);
            $reversed = array_reverse($array);
            foreach ($reversed as $item) {
                $ar = date('m.Y', $item);
                foreach ($result as $value) {
                    if ($value->month == $ar) {
                        $reports[] = $value;
                    }
                }
            }
        }
        return view('reports.room_reports', ['reports' => $reports]);
    }

    public function objView(Request $request)
    {
        // Подробный отчёт с календарём занятых дат определённого объекта по id
        $data = Room::where('id', $request->id)->first(); // Получили данные объекта
        $date_b = DateController::getBookingDatesId($request->id);  // Получение забронированных дат по ID номера
        $res = Setting::get('rule'); // Получам строку из БД правил календаря типа 2&2&25
        $rules = explode('&', $res[0]->rule);// Получаем массив правил для календаря, где:
        // 0 => (c какого дня разрешено бронировать: 1 - сегодня; 2 - завтра)
        // 1 => (минимальное количество дней)
        // 2 => (максимальное количество дней)

        $count_night = GetController::getCountNight($request->id);// Количество ночей
        $sum = GetController::getSum($request->id); // Получили сумму
        $last_month = date('m.Y', strtotime("-1 Months")); // Прошлый месяц
        $last_report = Report::where('room_id', $request->id)->where('month', $last_month)->first();// Отчёт за
        //прошлый месяц

        // Формируем дату старта для календаря
        if (!empty($rules['2']) && $rules['2'] == 1) {
            $start = date("Y-m-d");
        } else {
            $d = strtotime("+1 day");
            $start = date("Y-m-d", $d);
        }
        return view('reports.reports_obj', ['last_report' => $last_report, 'sum' => $sum, 'count_night' => $count_night, 'data' => $data, 'start' => $start, 'date_book' => $date_b ['date_book'], 'rules' => $rules]);
    }

    public function paid(Request $request)
    {
        // Внесение изменения оплаты в таблицу reports. По умолчанию 1(не оплачено), 2 - Оплачено
        Report::where('room_id', $request->id)->where('month', $request->month)->update(['paid' => 2]);
        self::setPayment($request->id, $request->paid, 1);
        return redirect()->back()->with(['id' => $request->id]);
    }

    public function paidCancel(Request $request)
    {
        //Отмена внесения оплаты
        Report::where('room_id', $request->id)->where('month', $request->month)->update(['paid' => 1]);
        self::setPayment($request->id, $request->paid, 2);
        return redirect()->back()->with(['id' => $request->id]);
    }

    public static function setPayment(int $user_id, int $sum, int $condition)
    {
        // Изменение и запись в таблицу новых суммы и количества ночей.
        //  $condition - действие плюс или минус основной суммы. 1 - плюс; 2 - минус;
        $payment = Payment::where('user_id', $user_id)->get();
        if (!empty(count($payment))) {
            if ($condition == 1) {
                $new_sum = $payment[0]-> payment + $sum;
            } else {
                $new_sum = $payment[0]-> payment - $sum;
            }
            Payment::where('user_id', $user_id)->update(['payment' => $new_sum]);
        } else {
            Payment::insert(['user_id' => $user_id, 'payment' => $sum]);
        }
    }

    public function paidConfirm(Request $request){

        Report::where('room_id', $request->id)->where('month', $request->month)->update(['confirm'=>2]);
        return redirect()->back()->with(['id'=>$request->id]);
    }

}
