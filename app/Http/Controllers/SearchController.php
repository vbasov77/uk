<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Image;
use App\Models\Room;
use Illuminate\Http\Request;


class SearchController extends Controller
{
    public function view()
    {
        return view('/');
    }

    public function searchRoom(Request $request)
    {
        // Поиск объектов по дате и количеству человек

        $request->validate([
            'date_book' => 'required'
        ]);
        $request->session()->put('date_book', $request->date_book);// Добавиди дату в сессию
        $request->session()->save();
        $request->session()->put('people', $request->people); // Добавили количество человек в сессию
        $request->session()->save();
        $all_booking = Booking::all(); // Получили массив всех бронирований
        $all_rooms = Room::all(); // Получили все объекты

        // Дата имеет вид 20.10.2022 - 25.10.2022. Удаляем пробелы и формируем массив
        // для получения всех дат в промежутке
        $dates = explode('-', preg_replace("/\s+/", "", $request->date_book));
        $start_dat = $dates[0];
        $end_dat = $dates[1];
        $dates_arr = DateController::getDates($start_dat, $end_dat); // Получили массив дат в промежутке.

        // Из 20.10.2022 - 25.10.2022 в 20.10.2022, 21.10.2022, 22.10.2022 ... 25.10.2022
        $busy_dates = []; // Формируем массив забронированных дат
        $busy_dates = false;
        foreach ($all_booking as $item) {
            foreach ($dates_arr as $value) {
                $start_date = strtotime($item->no_in); // начальная дата
                $end_date = strtotime($item->no_out); // конечная дата
                $date = strtotime($value);
                // Если промежуточная дата входит в промежуток начальной и конечной даты,
                // то записываем id объектов в массив занятых дат.
                if ($date >= $start_date && $date <= $end_date) {
                    $busy_dates[] = $item->room;
                    break;
                }
            }
        }

        // Для того, чтобы вывести все незанятые объекты - исключим из основного массива id все занятые id,
        // а потом сформируем массив объектов по оставшимся id

        // Если имеются занятые даты, то формируем массив объектов для вывода
        if ($busy_dates !== false) {
            $numbers_room = [];
            // формируем массив из id
            foreach ($all_rooms as $value) {
                $numbers_room[] = $value->id;
            }
            // Удаляем id занятых дат из основного массивы id
            foreach ($busy_dates as $item) {
                unset($numbers_room[array_search($item, $numbers_room)]);
            }

            // Формируем объекты по id и допустимому количеству человек
            $data = [];
            foreach ($numbers_room as $number) {
                for ($i = 0; $i < count($all_rooms); $i++) {
                    if ($number == $all_rooms [$i]->id && $all_rooms[$i]->capacity >= $request->people) {
                        $data[] = $all_rooms[$i];
                    }
                }
            }
            // Добавляем первое фото для каждого объекта
            $data = ImagesController::getDataWithPhoto($data);

        } else {
            // Если нет занятых дат, то выводим все объекты допустимые по количеству человек
            $data = [];
            for ($i = 0; $i < count($all_rooms); $i++) {
                if ($all_rooms[$i]->capacity >= $request->people) {
                    $data[] = $all_rooms[$i];
                }
            }
            // Добавляем первое фото для каждого объекта
            $data = ImagesController::getDataWithPhoto($data);
        }

        return view('/search.search_room', ['data' => $data]);
    }


}
