<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Reports;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DateController extends Controller
{

    public static function setCountNightObj(array $date, int $room_id, int $sum, int $condition)
    {
        // Изменение записи в базе reports, если таковой нет, то создание.
        $in = date("m.Y", strtotime($date[0]));
        $out = date("m.Y", strtotime($date[1]));
        if ($in === $out) {
            $month = $in;
            $count_night = GetController::countNight($date[0], $date[1]);
            $result = Reports::where('room_id', $room_id)->where('month', $month)->get();
            $data = [];
            if (!empty(count($result))) {
                $data[] = $result[0]->count_night;
                $data[] = $result[0]->sum;
            }
            self::setReportInTable($data, $room_id, $count_night, $sum, $month, $condition);
        } else {
            // Если даты захватывают два месяца, то разбиваем диапазон дат на два массива по каждому месяцу,
            $end_date_first_arr = date('t', strtotime($date[0])) . "." . date('m.Y', strtotime($date[0]));// Получаем последнюю дату месяца 31.02.2022
            $fist_array = GetController::getDatesArray($date[0], $end_date_first_arr);// Получаем массив дат первого массива
            unset($fist_array[0]);
            if (count($fist_array)) {
                //Получаем сумму по датам первого массива
                $sum_first_ar = [];
                foreach ($fist_array as $value) {
                    $sum_first_ar [] = Schedule::where('room', $room_id)->where('date_book', $value)->value('cost');
                }
                $sum_first = array_sum($sum_first_ar);// Вся сумма первого массива
                $month_first = date('m.Y', strtotime($date[0]));
                $result = Reports::where('room_id', $room_id)->where('month', $month_first)->get();
                $data = [];
                if (!empty(count($result))) {
                    $data[] = $result[0]->count_night;
                    $data[] = $result[0]->sum;
                }
                $count_night_first = GetController::countNight($date[0], $end_date_first_arr);
                self::setReportInTable($data, $room_id, $count_night_first, $sum_first, $month_first, $condition);

            }
            // Получаем сумму второго массива
            $first_date_second_arr = "01." . date('m.Y', strtotime($date[1]));// Получаем первую  дату месяца 01.02.2022
            $second_array = GetController::getDatesArray($first_date_second_arr, $date[1]);// Получаем массив дат
            if (count($second_array)) {
                $sum_second_ar = [];
                foreach ($second_array as $item) {
                    $sum_second_ar [] = Schedule::where('room', $room_id)->where('date_book', $item)->value('cost');
                }
                $sum_second = array_sum($sum_second_ar);// Вся сумма второго массива
                $month_second = date('m.Y', strtotime($date[1]));
                $count_night_second = GetController::countNight($first_date_second_arr, $date[1]) + 1;
                $res = Reports::where('room_id', $room_id)->where('month', $month_second)->get();
                $data_second = [];
                if (!empty(count($res))) {
                    $data_second[] = $res[0]->count_night;
                    $data_second[] = $res[0]->sum;
                }
                self::setReportInTable($data_second, $room_id, $count_night_second, $sum_second, $month_second, $condition);
            }
        }
    }

    public static function setReportInTable(array $data, int $room_id, int $count_night, int $sum, string $month, $condition)
    {
        //Массив $data должен состоять из двух элементов - количество ночей и суммы, если таковые имеются в BD.
        if (!empty(count($data))) {
            if ($condition == 1) {
                $night = $data[0] + $count_night;
                $new_sum = $data[1] + $sum;
            } elseif ($condition == 2) {
                $night = $data[0] - $count_night;
                $new_sum = $data[1] - $sum;
            }
            Reports::where('room_id', $room_id)->where('month', $month)->update(
                [
                    'count_night' => $night,
                    'sum' => $new_sum
                ]);
        } else {
            Reports::insert([
                'room_id' => $room_id,
                'count_night' => $count_night,
                'sum' => $sum,
                'month' => $month
            ]);
        }

    }

    public static function getDates(string $startTime, string $endTime)
    {
        // Получение массива дат в указанном диапазоне
        $day = 86400;
        $format = 'd.m.Y';
        $startTime = strtotime($startTime);
        $endTime = strtotime($endTime);
        $numDays = round(($endTime - $startTime) / $day); // без +1
        $days = [];
        for ($i = 1; $i < $numDays; $i++) {
            $days[] = date($format, ($startTime + ($i * $day)));
        }
        return $days;
    }

    public static function checkDates()
    {
        Booking::all();
    }

    public static function plusCost($sum_night)
    {
        // Увеличение суммы в зависимости от количества ночей
        if ($sum_night <= 1) {
            $c = 840;
        } elseif ($sum_night > 1 && $sum_night <= 3) {
            $c = 340;
        } elseif ($sum_night > 3 && $sum_night <= 5) {
            $c = 140;
        } else ($c = 0);
        return $c;

    }

    public static function getBookingDatesId(int $room)
    {
        // Получение забронированных дат по ID номера
        $result = Booking::where('room', $room)->get();
        if (!empty($result)) {
            // Переформатирование date_book
            $dis_s = [];
            $dis_a = [];
            for ($i = 0; $i < count($result); $i++) {
                $dis = explode(',', $result [$i]->date_book);
                foreach ($dis as $item) {
                    $dis_s [] = date("Y-m-d", strtotime($item));
                }
                $dis_a[] = implode(',', $dis_s);
            }
            $date_book = implode(',', $dis_s);
            // Переформатирование no_in
            $dis_n = [];
            $dis_i = [];
            for ($ii = 0; $ii < count($result); $ii++) {
                $diss = explode(',', $result[$ii]->no_in);
                foreach ($diss as $val) {
                    $dis_n [] = date("Y-m-d", strtotime($val));
                }
                $dis_i[] = implode(',', $dis_n);
            }
            $no_in = implode(',', $dis_n);
            // Переформатирование no_out;
            $dis_o = [];
            $dis_t = [];
            for ($li = 0; $li < count($result); $li++) {
                $disss = explode(',', $result [$li]->no_out);
                foreach ($disss as $v) {
                    $dis_o[] = date("Y-m-d", strtotime($v));
                }
                $dis_t[] = implode(',', $dis_o);
            }
            $no_out = implode(',', $dis_o);
        } else {
            $date_book = "";
            $no_in = "";
            $no_out = "";
        }
        $data = [
            'date_book' => $date_book,
            'no_in' => $no_in,
            'no_out' => $no_out,
        ];

        return $data;
    }

    public static function getBookingDates()
    {
        // Получение всех забронированных дат
        $result = Booking::all();
        if (!empty($result)) {
            // Переформатирование date_book
            $dis_s = [];
            $dis_a = [];
            for ($i = 0; $i < count($result); $i++) {
                $dis = explode(',', $result [$i]->date_book);
                foreach ($dis as $item) {
                    $dis_s [] = date("Y-m-d", strtotime($item));
                }
                $dis_a[] = implode(',', $dis_s);
            }
            $date_book = implode(',', $dis_s);

            // Переформатирование no_in
            $dis_n = [];
            $dis_i = [];
            for ($ii = 0; $ii < count($result); $ii++) {
                $diss = explode(',', $result[$ii]->no_in);
                foreach ($diss as $val) {
                    $dis_n [] = date("Y-m-d", strtotime($val));
                }
                $dis_i[] = implode(',', $dis_n);
            }
            $no_in = implode(',', $dis_n);
            // Переформатирование no_out;
            $dis_o = [];
            $dis_t = [];
            for ($li = 0; $li < count($result); $li++) {
                $disss = explode(',', $result [$li]->no_out);

                foreach ($disss as $v) {
                    $dis_o[] = date("Y-m-d", strtotime($v));
                }
                $dis_t[] = implode(',', $dis_o);
            }
            $no_out = implode(',', $dis_o);
        } else {
            $date_book = "";
            $no_in = "";
            $no_out = "";
        }

        $data = [
            'date_book' => $date_book,
            'no_in' => $no_in,
            'no_out' => $no_out,
        ];

        return $data;
    }


    public static function inOrder()
    {
        $res = Booking::all();
        if (!empty(count($res))) {
            for ($i = 0; $i < count($res); $i++) {
                $arr[] = strtotime($res[$i]->no_in);
            }
            sort($arr);
            foreach ($arr as $item) {
                $ar = date('d.m.Y', $item);
                foreach ($res as $value) {
                    if (strtotime($ar) == strtotime($value->no_in)) {
                        $array [] = $value;
                    }
                }
            }
        } else {
            $array = "";
        }
        return $array;
    }


}
