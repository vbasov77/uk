<?php

namespace App\Http\Controllers;


use App\Models\Rooms;
use App\Models\Settings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class FrontController extends Controller
{
    public function view(Request $request)
    {
        $request->session()->forget(['date_book', 'people']);
        $res = Settings::get('rule');
        $rules = explode('&', $res [0]->rule);
        if (!empty($rules[0]) && (int)$rules [0] == 1) {
            $start = date("Y-m-d");
        } else {
            $d = strtotime("+1 day");
            $start = date("Y-m-d", $d);
        }
        $front_data = Settings::find(1);
        $front = explode('&', $front_data->front);
        $objects = Rooms::all();
        $data = Rooms::all();
        return view('front', ['data'=>$data, 'front' => $front, 'rules' => $rules, 'start' => $start, 'objects' => $objects]);
    }


}
