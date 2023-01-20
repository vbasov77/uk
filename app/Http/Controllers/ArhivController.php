<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArhivController extends Controller
{


    public function oneView(int $id)
    {
        $data = Archive::find($id);
        return view('archive.one_view', ['data' => $data]);
    }


    public function inArchive(Request $request)
    {
        // Переносим бронирование в архив

        $result = Booking::where('id', $request->id)->first();// Получили данные бронирования по id для переноса в архив
        $data = [
            'name_user' => $result->name_user,
            'phone_user' => $result->phone_user,
            'email_user' => $result->email_user,
            'no_in' => $result->no_in,
            'no_out' => $result->no_out,
            'user_info' => $result->user_info,
            'summ' => $result->summ,
            'pay' => $result->pay,
            'info_pay' => $result->info_pay,
            'confirmed' => $result->confirmed,
            'otz' => $request->otz
        ];
        Archive::insert($data); // Добавили в архив
        Booking::where('id', $request->id)->delete(); // Удалили из базы данных бронирования
        return redirect()->action('OrderController@view');
    }


}
