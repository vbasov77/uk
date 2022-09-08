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


    public function inArchive()
    {
        $result = Booking::where('id', $_POST['id'])->get();
        $data = [
            'name_user' => $result[0]-> name_user,
            'phone_user' => $result[0]-> phone_user,
            'email_user' => $result[0]-> email_user,
            'no_in' => $result[0]-> no_in,
            'no_out' => $result[0]-> no_out,
            'user_info' => $result[0]-> user_info,
            'summ' => $result[0]-> summ,
            'pay' => $result[0]-> pay,
            'info_pay' => $result[0]-> info_pay,
            'confirmed' => $result[0]-> confirmed,
            'otz' => $_POST['otz']
        ];
        Archive::insert($data);
        Booking::where('id', $_POST['id'])-> delete();
        return redirect()->action('OrdersController@view');
    }


}
