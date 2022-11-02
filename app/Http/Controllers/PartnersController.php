<?php

namespace App\Http\Controllers;


use App\Models\Payment;
use App\Models\Rooms;
use Illuminate\Http\Request;


class PartnersController extends Controller
{
    public function view(Request $request)
    {
        // Получение данных для отчёта партнёрам
        $rooms = Rooms::where('user_id', $request->user()->id)->get();// Получили все объекты партнёра
        $payment = Payment::where('user_id', $request->user()->id)->value('payment');// Получили выплаты для портнёров
        if(empty($payment)){
            $payment = 0;
        }
        return view('reports.my_objects', ['data' => $rooms, 'payment'=>$payment]);
    }
}
