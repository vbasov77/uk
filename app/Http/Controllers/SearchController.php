<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Rooms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function view()
    {
        return view('/');
    }

    public function searchRoom(Request $request)
    {
        $request->session()->put('date_book', $_POST['date_book']);
        $request->session()->save();
        $request->session()->put('people', $_POST['people']);
        $request->session()->save();
        $arr_room = Booking::all();
        $d = explode('-', preg_replace("/\s+/", "", $_POST['date_book']));// удалили пробелы и сформировали массив
        $startTime = $d [0];
        $endTime = $d [1];
        $dd = DateController::getDates($startTime, $endTime);
        $no_room = [];
        foreach ($arr_room as $a) {
            foreach ($dd as $value) {
                $start_date = strtotime($a->no_in); // начальная дата
                $end_date = strtotime($a->no_out); // конечная дата
                $date = strtotime($value);
                if ($date >= $start_date && $date <= $end_date) {
                    $no_room[] = $a ->room;
                }
            }
        }
        if (!empty($no_room)) {
            $no_room = array_unique($no_room);
        } else {
            $no_room = false;
        }
        if ($no_room !== false) {
            $res =  Rooms::get('id');
            $number_room = [];
            foreach ($res as $re) {
                $number_room[] = $re['id'];
            }
            foreach ($no_room as $item) {
                unset($number_room[array_search($item, $number_room)]);
            }
            $all_j = Rooms::get();
            $data = [];
            foreach ($number_room as $number) {
                for ($i = 0; $i < count($all_j); $i++) {
                    if ($number == $all_j [$i]->id && $all_j[$i]->capacity >= $_POST ['people']) {
                        $data[] = $all_j[$i];
                    }
                }
            }
        } else {
            $dat = Rooms::get();
            $data = [];
            for ($i = 0; $i < count($dat); $i++) {
                if ($dat[$i]->capacity >= $_POST ['people']) {
                    $data[] = $dat[$i];
                }
            }
        }
        return view('/search.search_room', ['data' => $data]);
    }

}
