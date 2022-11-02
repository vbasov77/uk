<?php

namespace App\Http\Controllers;

use App\Models\Images;
use App\Models\Rooms;
use App\Models\Schedule;
use Illuminate\Http\Request;


class ScheduleController extends Controller
{
    public function view()
    {
        // Вывод всех объектов в таблицу для редактирования и добавления стоимость за сутки
        $rooms = Rooms::all();
        $photos = Images::all();
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
            // Формирование данных для добавления новых цен за сутки
            // С использованием календаря для выбора нескольких дат
            $schedule = Schedule::where('room', $request->id)->get(); // Получили все занятые дни для календаря
            $nam = Rooms::where('id', $request->id)->value('title'); // Получили название объекта

            // Если есть цены, то формируем массив занятых дат для календаря
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
            return view('schedule.schedule')->with(['date_book' => $date_book, 'id' => $request->id, 'name_room' => $nam]);
        }

        // этот код выполнится, если используется метод POST
        if ($request->isMethod('post')) {
            // Добавление расписания
            $id = $request->room;
            $result = Schedule::where('room', $id)->get();
            $arr_date = [];
            foreach ($result as $res) {
                $arr_date[] = $res->date_book;
            }
            $d = $request->date_book;
            $d = preg_replace("/\s+/", "", $d);// удалили пробелы
            $dd = explode("-", $d);// Преобразовали в массив
            $startTime = $dd[0];
            $endTime = $dd[1];
            $date_b = DateController::getDates($startTime, $endTime);// Получили промежуточные даты
            $cost = $request->cost;
            $stat = 0;
            $dates = [];
            $dates [] = $startTime;
            foreach ($date_b as $b) {
                $dates [] = $b;
            }
            $dates [] = $endTime;
            foreach ($dates as $date) {
                if (empty(in_array($date, $arr_date))) {
                    Schedule::insert([
                        'room' => $request->room,
                        'date_book' => $date,
                        'cost' => $cost,
                        'stat' => $stat
                    ]);

                } else {
                    Schedule::where('date_book', $date)->update([
                        'room' => $request->room,
                        'date_book' => $date,
                        'cost' => $cost,
                        'stat' => $stat
                    ]);
                }

            }

            $res = Schedule::all();
            if (!empty($res)) {
                // Переформатирование date_book
                $dis_s = [];
                $dis_a = [];
                for ($i = 0; $i < count($res); $i++) {
                    if ($res[$i]->stat == 0) {
                        $dis = explode(',', $res [$i]->date_book);
                        foreach ($dis as $item) {
                            $dis_s [] = date("Y-m-d", strtotime($item));
                        }
                        $dis_a[] = implode(',', $dis_s);
                    } elseif ($res[$i]->stat == 1) {
                        $dis_ii = explode(',', $res [$i]->date_book);
                        foreach ($dis_ii as $item) {
                            $dis_io [] = date("Y-m-d", strtotime($item));
                        }
                        $dis_in[] = implode(',', $dis_io);
                    } else {
                        $dis_o = explode(',', $res [$i]->date_book);
                        foreach ($dis_o as $item) {
                            $dis_ou [] = date("Y-m-d", strtotime($item));
                        }
                        $dis_out[] = implode(',', $dis_ou);
                    }
                }
                $date_book = implode(',', $dis_s);

            } else {
                $date_book = "";

            }
            $nam = Rooms::where('id', $id)->value('title');
            return view('schedule.schedule')->with(['date_book' => $date_book, 'id' => $id, 'name_room' => $nam]);
        }
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
            $nam = Rooms::where('id', $request->id)->value('title');
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
