<?php

namespace App\Http\Controllers;

use App\Models\Rooms;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class RoomsController extends Controller
{
    public function view(int $id)
    {
        $data = Rooms::find($id);
        $photo = explode(',', $data->photo_room);
        $role = Auth::user()->role;
        $date_b = DateController::getBookingDatesId($id);
        $res = Settings::get('rule');
        $rules = explode('&', $res [0]->rule);
        if (!empty($rules['2']) && $rules['2'] == 1) {
            $start = date("Y-m-d");
        } else {
            $d = strtotime("+1 day");
            $start = date("Y-m-d", $d);
        }
        return view('rooms.view', ['start' => $start, 'data' => $data, 'photo' => $photo, 'role' => $role, 'date_book' => $date_b ['date_book'], 'rules' => $rules]);
    }

    public function viewAdd()
    {
        return view('rooms.view_add');
    }

    public function viewEdit(Request $request, $id)
    {
        $result = Rooms::find($id);
        $photo_room = $result->photo_room;
        $arr_sess = \session('file');
        $rooms = false;
        if (!empty($photo_room) == null && !empty($arr_sess)) {
            foreach ($arr_sess as $arr_s) {
                Session::put('file', array_diff(Session::get('file'), [$arr_s]));
            }
            $rooms = false;
        }
        if (!empty($photo_room) == null && empty($arr_sess)) {
            $rooms = false;
        }
        if (!empty($photo_room) != null && !empty($arr_sess)) {
            $a = explode(',', $photo_room);
            foreach ($arr_sess as $arr_s) {
                Session::put('file', array_diff(Session::get('file'), [$arr_s]));
            }
            foreach ($a as $value) {
                $request->session()->push('file', $value);
            }
            $rooms = \session('file');
        }

        if (!empty($photo_room) != null && empty($arr_sess)) {
            $a = explode(',', $photo_room);
            foreach ($a as $value) {
                $request->session()->push('file', $value);
            }
            $rooms = \session('file');
        }
        $request->session()->put('id', (int)$id);
        $request->session()->save();
        return view('/rooms/view_edit', ['data' => $result, 'rooms' => $rooms]);
    }

    public function editRoom(Request $request)
    {
        if (!empty(session('file'))) {
            $res = implode(',', session('file'));
            Rooms::where('id', \session('id'))->update([
                'name_room' => $_POST['name_room'],
                'user_id' => $_POST['user_id'],
                'address' => $_POST['address'],
                'price' => $_POST['price'],
                'text_room' => $_POST['text_room'],
                'capacity' => $_POST['capacity'],
                'service' => $_POST['service'],
                'video' => $_POST['video'],
                'photo_room' => $res,
                'coordinates' => $_POST['coordinates'],
            ]);
            $res = ['answer' => 'ok'];
        } else {
            $res = $res = ['answer' => 'error'];
        }
        exit(json_encode($res));
    }


    public function getCost()
    {
        $date_u = preg_replace("/\s+/", "", $_POST['date_book']);// удалили пробелы
        $date_u = explode("-", $date_u);
        $d = DbController::GetScheduleTable();
        $arr_date = DateController::getDates($date_u[0], $date_u[1]);
        $dat = [];
        $dat[] = $date_u[0];
        $cost = [];
        foreach ($arr_date as $value) {
            $dat [] = $value;
        }
        $date_view = [];
        foreach ($d as $item) {
            $str_arr = $item['date_book'];
            if (!empty(in_array($str_arr, $dat)) && $item['room'] === $_POST['room']) { // проверка есть ли в массиве
                $cumm_cost = $item['cost'];
                $date_view[] = $item ['date_book'] . "/" . $cumm_cost;
                $cost[] = $item['cost'];
            }
        }
        $dates = implode(',', $date_view);
        $sum = array_sum($cost);
        $nights = count($date_view);
        $res = ['answer' => 'ok', 'date_book' => $dates, 'summ' => $sum, 'nigh' => $nights /*, 'dates' => implode(',', $date_view), 'summ' => $sum*/];
        exit(json_encode($res));
    }


    public function addRoom()
    {
        if (!empty($_POST)) {
            $id = Rooms::insertGetId([
                'name_room' => $_POST['name_room'],
                'user_id' => $_POST['user_id'],
                'address' => $_POST['address'],
                'price' => $_POST['price'],
                'text_room' => $_POST['text_room'],
                'capacity' => $_POST['capacity'],
                'service' => $_POST['service'],
                'video' => $_POST['video'],
                'coordinates' => $_POST['coordinates'],

            ]);
            return redirect()->action('RoomsController@viewEdit', ['id' => $id]);
        }
    }


}
