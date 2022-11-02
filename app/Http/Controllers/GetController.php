<?php

namespace App\Http\Controllers;

use App\Models\Images;
use App\Models\Reports;
use App\Models\Video;
use Illuminate\Http\Request;

class GetController extends Controller
{
    public static function getCountNight(int $room_id)
    {
        // Получение количества занятых дней определённого объекта текущего месяца
        $count = Reports::where('room_id', $room_id)->where('month', date('m.Y'))->get();
        if (empty(count($count))) {
            $count_night = 0;
        } else {
            $count_night = $count[0]->count_night;
        }
        return $count_night;
    }

    public static function getPathVideoRoom(int $room_id)
    {
        // Получение всех видеозаписей определённого номера из БД video
        $result = Video::where('room_id', $room_id)->get();
        if (!empty(count($result))) {
            $video = $result[0]->path;
        } else {
            $video = null;
        }
        return $video;
    }

    public static function getImages(int $room_id)
    {
        // Получаем массив всех фото по id номера
        $result = Images::where('room_id', $room_id)->get();
        $img = null;
        if (!empty(count($result))) {
            foreach ($result as $value) {
                $img[] = $value->path;
            }
        }
        return $img;
    }


    public static function countNight(string $start, string $end)
    {
        // Подсчёт количества ночей
        $start_time = strtotime($start);
        $end_time = strtotime($end);
        $time_diff = abs($end_time - $start_time);
        $numberDays = $time_diff / 86400;  // 86400 секунд в одном дне
        $count_night = intval($numberDays);
        return $count_night;
    }

    public static function getDatesArray(string $first, string $second)
    {
        //Получение дат диапазона 29.01.2022 - 03.02.2022
        $day = 86400;
        $start = strtotime($first . ' -1 days');
        $end = strtotime($second . ' +1 days');
        $nums = round(($end - $start) / $day);
        $days = [];
        for ($i = 1; $i < $nums; $i++) {
            $days[] = date('d.m.Y', ($start + ($i * $day)));
        }
        return $days;
    }

    public static function getSum(int $room_id)
    {
        // Получение всей суммы определённого объекта за текущий месяц из БД - Отчёты (reports)
        $result = Reports::where('room_id', $room_id)->where('month', date('m.Y'))->get();
        if (empty(count($result))) {
            $sum = 0;
        } else {
            $sum = $result[0]->sum;
        }
        return $sum;
    }
}
