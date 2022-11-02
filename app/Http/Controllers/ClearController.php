<?php

namespace App\Http\Controllers;

use App\Models\Schedule;

class ClearController extends Controller
{
    public  function clearSchedule()
    {
        $date = strtotime(date('d.m.Y', time() - 86400));
        $schedule = Schedule::all();
        $count = 0;
        foreach ($schedule as $value){
            if(strtotime($value->date_book) <= $date){
                Schedule::where('date_book', $value->date_book)->delete();
                $count +=1;
            }
        }

        return view('clear.clear_schedule', ['count'=> $count]);
    }

}
