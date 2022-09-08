<?php

namespace App\Http\Controllers;

use App\Models\Rooms;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ScheduleController extends Controller
{
    public function view()
    {
        $rooms = Rooms::get();
        return view('schedule.view', ['rooms' => $rooms]);
    }

    public function add($id)
    {
        $schedule = Schedule::where('room', $id)->get();
        $nam = Rooms::where('id', $id)->find($id, ['name_room']);
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
        return view('schedule.schedule')->with(['date_book' => $date_book, 'id' => $id, 'name_room' => $nam->name_room]);
    }

    public function edit()
    {
        $q = preg_replace("/\s+/", "", $_POST['date_book']);
        $dates = explode('-', $q);
        $arr_date = DateController::getDates($dates[0], $dates[1]);
        $arr = [];
        $arr[] = $dates[0];
        foreach ($arr_date as $value) {
            $arr[] = $value;
        }
        $arr[] = $dates[1];
        $result = Schedule::where('room', $_POST['room'])->get();
        foreach ($result as $item) {
            if (in_array($item->date_book, $arr)) {
                $data[] = $item;
            }
        }
        $arr_resDat = [];
        foreach ($result as $r) {
            $arr_resDat[] = $r->date_book;
        }
        foreach ($arr as $a) {
            if (empty(in_array($a, $arr_resDat))) {
                $noDates [] = $a;
            } else {
                $noDates = false;
            }
        }

        return view('schedule.edit_table', ['datas' => $data, 'noDates' => $noDates]);
    }

    public function editTable()
    {
        $data = [];
        for ($i = 0; $i < count($_POST['cost']); $i++) {
            $data[] = [$_POST['id'][$i], $_POST['cost'][$i]];
        }
        foreach ($data as $datum) {
           Schedule::where('id', $datum[0])->update(['cost' => $datum[1]]);
        }
        $message = "Изменения сохранены";
        return redirect()->action('ScheduleController@view', ['message' => $message]);
    }


    public function viewEdit(int $id)
    {
        $schedule = DB::table('schedule')->where('room', $id)->get();

        if (!empty($schedule)) {
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
        $nam = DB::table('rooms')->where('id', $id)->value('name_room');
        return view('schedule.edit')->with(['date_book' => $date_book, 'id' => $id, 'name_room' => $nam]);
    }


    public function schedule()
    {
        $id = $_POST ['room'];

        $result = Schedule::where('room', $id)->get();
        $arr_date = [];
        foreach ($result as $res) {
            $arr_date[] = $res->date_book;
        }
        $d = $_POST ['date_book'];
        $d = preg_replace("/\s+/", "", $d);// удалили пробелы
        $dd = explode("-", $d);// Преобразовали в массив
        $startTime = $dd[0];
        $endTime = $dd[1];
        $date_b = DateController::getDates($startTime, $endTime);// Получили промежуточные даты
        $cost = $_POST['cost'];
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
                    'room' => $_POST ['room'],
                    'date_book' => $date,
                    'cost' => $cost,
                    'stat' => $stat
                ]);

            } else {
                Schedule::where('date_book', $date)->update([
                    'room' => $_POST ['room'],
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
        $nam = Rooms::where('id', $id)->value('name_room');
        return view('schedule.schedule')->with(['date_book' => $date_book, 'id' => $id, 'name_room' => $nam]);
    }


}
