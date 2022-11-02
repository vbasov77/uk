<?php

namespace App\Http\Controllers;

use App\Models\Images;
use App\Models\Rooms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;


class FileController extends Controller
{
    function uploadDrop(Request $request)
    {
        // Загрузка фото Ajax с использованием dropzone + добавление в BD
        if (!empty($request->file())) {
            $image = $request->file('file');
            // Допустимые символы для уникального имени файла
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $file = $request->file('file');
            // Создаём уникальное имя для файла
            $filename = substr(str_shuffle($permitted_chars), 0, 16) . '.' . $file->extension();
            // Добавление файла в папку public
            $image->move(public_path('images'), $filename);
            // Записываем имя файла в BD
            Images::insert([
                'room_id' => $request->id,
                'path'=> $filename,

            ]);
            // Получаем все фото объекта
            $result = Images::where('room_id', $request->id)->get();
            foreach ($result as $value){
                $array[] = $value->path;
            }
            $data = implode(',', $array);
            $fil = (string) $data;
            $res = ['answer' => 'ok', 'fil' => $fil];
        } else {
            $res = ['answer' => 'error', 'mess' => 'Ошибка'];
        }
        exit(json_encode($res));
    }

    public function deleteSess(Request $request)
    {

        // Удаление сессий
        $id = (int)\session('id');
        $r = Rooms::where('id', $id)->get('photo_room');
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
        // Удаление файла фото из public и из BD
        if ($request->get('file')) {
            $file = $request->get('file');
            File::delete(public_path('images/' . $request->get('file')));// Удалили файл
            Images::where('room_id', $request->id)->where('path', $file)->delete();// Удалили из БД
        }
    }
}
