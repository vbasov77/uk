<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Report;
use App\Models\Schedule;

class DateController extends Controller
{

    public static function setCountNightObj(array $date, int $room_id, int $sum, int $condition)
    {
        // Изменение записи в базе reports(отчёты), если таковой нет, то создание.
        // Дата в БД имеет формат - 01.2022
        // Сумма прибыли должна записываться в соответствии месяца. Поэтому, если бронирование захватывает два месяца,
        // то разбиваем бронирование, достаём цену, суммируем и записываем в БД в соответствии месяца и id объекта

        $in = date("m.Y", strtotime($date[0])); // изменили формат даты въезда с 25.09.2022 в 09.2022
        $out = date("m.Y", strtotime($date[1]));// изменили формат даты выезда с 02.10.2022 в 10.2022

        // Сравним месяцы, если они совпадают, то суммируем либо вычетаем сумму, в зависимости от заданного действия
        // переданного в переменной $condition: 1 - прибавление, 2 - вычетание
        if ($in === $out) {
            $result = Report::where('room_id', $room_id)->where('month', $in)->get();
            $data = [];
            if (!empty(count($result))) {
                $data[] = $result[0]->count_night;
                $data[] = $result[0]->sum;
            }
            $count_night = GetController::countNight($date[0], $date[1]); // получили количество ночей
            self::setReportInTable($data, $room_id, $count_night, $sum, $in, $condition);
        } else {
            // Если даты захватывают два месяца, то разбиваем диапазон дат на два массива по каждому месяцу,

            // Получаем последнюю дату месяца 31.02.2022
            $end_date_first_arr = date('t', strtotime($date[0])) . "." . date('m.Y', strtotime($date[0]));
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
                $result = Report::where('room_id', $room_id)->where('month', $month_first)->get();
                $data = [];
                if (!empty(count($result))) {
                    $data[] = $result[0]->count_night;
                    $data[] = $result[0]->sum;
                }
                $count_night_first = GetController::countNight($date[0], $end_date_first_arr);
                self::setReportInTable($data, $room_id, $count_night_first, $sum_first, $month_first, $condition);

            }
            // Получаем сумму второго массива(второго месяца)
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
                $res = Report::where('room_id', $room_id)->where('month', $month_second)->get();
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
        // Изменение данных в БД в зависимости от переданного действия в переменной
        // $condition. 1 - сложение, 2 - вычетание

        // Массив $data должен состоять из двух элементов - количество ночей и суммы, если таковые имеются в BD.
        if (!empty(count($data))) {
            if ($condition == 1) {
                $night = $data[0] + $count_night;
                $new_sum = $data[1] + $sum;
            } elseif ($condition == 2) {
                $night = $data[0] - $count_night;
                $new_sum = $data[1] - $sum;
            }
            // Изменение данных по id в БД reports
            Report::where('room_id', $room_id)->where('month', $month)->update(
                [
                    'count_night' => $night,
                    'sum' => $new_sum
                ]);
        } else {
            Report::insert([
                'room_id' => $room_id,
                'count_night' => $count_night,
                'sum' => $sum,
                'month' => $month
            ]);
        }

    }

    public static function getDates(string $start_time, string $end_time)
    {
        // Получение массива дат в указанном диапазоне
        $day = 86400;
        $format = 'd.m.Y';
        $start_time = strtotime($start_time);
        $end_time = strtotime($end_time);
        $num_days = round(($end_time - $start_time) / $day); // без +1
        $days = [];
        for ($i = 1; $i < $num_days; $i++) {
            $days[] = date($format, ($start_time + ($i * $day)));
        }
        return $days;
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
        // Получение всех забронированных дат для отчета в админ панель
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
        // Формируем даты по порядку
        $result = Booking::all();
        if (!empty(count($result))) {
            for ($i = 0; $i < count($result); $i++) {
                $arr[] = strtotime($result[$i]->no_in);
            }
            sort($arr);
            foreach ($arr as $item) {
                $ar = date('d.m.Y', $item);
                foreach ($result as $value) {
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
