<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\TableCost;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;


class ScheduleController extends Controller
{
    public function view()
    {
        // Вывод всех объектов в таблицу для редактирования и добавления стоимость за сутки
        $rooms = Room::all();
        $photos = Image::all();
        foreach ($rooms as $room) {
            foreach ($photos as $photo) {
                if ($room->id == $photo->room_id) {
                    $room['path'] = $photo->path;
                    break;
                } else {
                    $room['path'] = null;
                }
            }
        }
        return view('schedule.view', ['rooms' => $rooms]);
    }

    public function add(Request $request)
    {
        // этот код выполнится, если используется метод GET
        if ($request->isMethod('get')) {
            $nam = Room::where('id', $request->id)->value('title'); // Получили название объекта
            $date_book = self::getBusyDates($request->id);
            return view('schedule.schedule')->with(['date_book' => $date_book, 'id' => $request->id, 'name_room' => $nam]);
        }

        // этот код выполнится, если используется метод POST
        if ($request->isMethod('post')) {
            $datesBook = preg_replace("/\s+/", "", $request->date_book);// удалили пробелы
            $datesBookArr = explode('-', $datesBook);// Преобразовали в массив
            $datesArr = [];
            $datesArr[] = $datesBookArr[0];
            $datesArray = DateController::getDates($datesBookArr[0], $datesBookArr[1]);// Получили промежуточные даты
            foreach ($datesArray as $str) {
                $datesArr[] = $str;
            }
            $datesArr[] = $datesBookArr[1];

            $oldStrDateAndCost = TableCost::where('obj_id', $request->room)->value('date_book');
            // Получили все данные дат и стоимости из БД

            $oldStrDateAndCost = explode(',', $oldStrDateAndCost);
            //Разбили на массив полученные данные

            // Создадим массив из одних дат
            $arrOnlyDate = [];
            foreach ($oldStrDateAndCost as $value) {
                $date = explode('/', $value);
                $arrOnlyDate[] = strtotime($date[0]);
            }

            // Перебираем новый массив. Если даты нет в массиве, то записываем строку в массив $oldStrDateAndCost
            for ($i = 0; $i < count($datesArr); $i++) {
                if (empty(in_array(strtotime($datesArr[$i]), $arrOnlyDate))) {
                    $oldStrDateAndCost[] = $datesArr[$i] . '/' . $request->cost;
                }
                //Если есть такая строка, то заменяем её с новой ценой
                if (!empty(in_array(strtotime($datesArr[$i]), $arrOnlyDate))) {
                    foreach ($datesArr as $item) {
                        for ($q = 0; $q < count($oldStrDateAndCost); $q++) {
                            $dat = strtotime(explode('/', $oldStrDateAndCost[$q]));
                            if ($dat == strtotime($item)) {
                                $oldStrDateAndCost[$q] = $item . '/' . $request->cost;
                            }
                        }
                    }
                }
            }
            $inTable = implode(',', $oldStrDateAndCost);
            TableCost::where('obj_id', $request->room)->update(['date_book' => $inTable]);
            return redirect()->action('ScheduleController@add', ['id' => $request->room]);

        }
    }

    public static function getBusyDates(int $idObj)
    {
        // Формирование данных для добавления новых цен за сутки
        // С использованием календаря для выбора нескольких дат
        $schedule = TableCost::where('obj_id', $idObj)->value('date_book'); // Получили все занятые дни для календаря
        // Если есть цены, то формируем массив занятых дат для календаря
        if (isset($schedule)) {
            $date_b = explode(',', $schedule);
            $busyDates = [];
            foreach ($date_b as $res) {
                $date = explode('/', $res);
                $busyDates[] = date("Y-m-d", strtotime($date[0]));
            }
            $date_book = implode(',', $busyDates);

        } else {
            $date_book = "";
        }
        return $date_book;
    }

    public function edit(Request $request)
    {
        // этот код выполнится, если используется метод GET
        if ($request->isMethod('get')) {
            // Формирование данных для редактирования цен
            $schedule = Schedule::where('room', $request->id)->get();
            if (!empty(count($schedule))) {
                $date_b = [];
                foreach ($schedule as $res) {
                    $date_b[] = $res->date_book;
                }
                $dis = [];
                foreach ($date_b as $item) {
                    $dis[] = date("Y-m-d", strtotime($item));
                }
                $date_book = implode(',', $dis);
            } else {
                $date_book = "";
            }
            $nam = Room::where('id', $request->id)->value('title');
            return view('schedule.edit')->with(['date_book' => $date_book, 'id' => $request->id, 'name_room' => $nam]);
        }

        // этот код выполнится, если используется метод POST
        if ($request->isMethod('post')) {
            // Изменение уже существующих цен посуточной аренды
            // Удаляем пробелы 29.10.2022 - 29.11.2022
            $q = preg_replace("/\s+/", "", $request->date_book);
            $dates = explode('-', $q); // Разбиваем диапазон дат на массив
            $arr_date = DateController::getDates($dates[0], $dates[1]);// Получам все даты из диапазона
            $arr_date[] = $dates[1];
            $result = Schedule::where('room', $request->room)->get();
            foreach ($result as $item) {
                // Если дата из БД существует в массиве выбранных дат, то записываем данные в массив
                if (in_array($item->date_book, $arr_date)) {
                    $data[] = $item;
                }
            }
            return view('schedule.edit_table', ['datas' => $data]);
        }
    }


    public function editTable(Request $request)
    {
        // Массовое изменение цен для объекта
        $data = [];
        for ($i = 0; $i < count($request->cost); $i++) {
            $data[] = [$request->id[$i], $request->cost[$i]];
        }
        foreach ($data as $value) {
            Schedule::where('id', $value[0])->update(['cost' => $value[1]]);
        }
        $message = "Изменения сохранены";
        return redirect()->action('ScheduleController@view', ['message' => $message]);
    }


}
