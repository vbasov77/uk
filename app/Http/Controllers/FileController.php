<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;


class FileController extends Controller
{
    public function index(Request $request)
    {
        var_dump($request);

    }


    function uploadDrop(Request $request)
    {
        if (!empty($_FILES)) {
            $image = $request->file('file');
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $file = $request->file('file');
            $filename = substr(str_shuffle($permitted_chars), 0, 16) . '.' . $file->extension();
            $image->move(public_path('images'), $filename);
            $request->session()->push('file', $filename);
            $request->session()->save();
            $d = session('file');
            $data = implode(',', $d);
            $fil = (string)$data;
            $res = ['answer' => 'ok', 'fil' => $fil];
        } else {
            $res = ['answer' => 'error', 'mess' => 'Ошибка'];
        }
        exit(json_encode($res));
    }

    public function deleteSess(Request $request)
    {
        $id = (int)\session('id');
        $r = DB::table('rooms')->where('id', $id)->get('photo_room');
        $result = json_decode(json_encode($r), true);
        $res = explode(',', $result[0]['photo_room']);
        $fil_sess = $request->session()->pull('file', 'default');
        foreach ($fil_sess as $re) {
            if (empty(in_array($re, $res))) {
                File::delete(public_path('images/' . $re));
            }
        }
        $request->session()->forget('file');
    }

    public function deleteDrop(Request $request)
    {
        if ($request->get('file')) {
            File::delete(public_path('images/' . $request->get('file')));
            $file = $request->get('file');
            Session::put('file', array_diff(Session::get('file'), [$file]));
            $id = (int)\session('id');
            $r = DB::table('rooms')->where('id', $id)->get('photo_room');
            $result = json_decode(json_encode($r), true);
            if (!empty($result[0]['photo_room']) != null) {
                $res = explode(',', $result[0]['photo_room']);
                unset($res[array_search($file,$res)]);
                $resul = implode(',', $res);
                DB::table('rooms')->where('id', \session('id'))->update(['photo_room' =>$resul]);
            }
        }
    }
}
