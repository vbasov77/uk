<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function view()
    {
        return view('/');
    }

    public function verificationUserBook(int $id)
    {
        $res = Booking::where('id', $id)->get();
        if (!empty($res)) {
            $user_info = explode(';', $res[0]-> user_info);
            $more_book = explode(',', $res[0]-> more_book);
            $sum_night = count($more_book) - 1;
            return view('verifications.book_user')->with(['res' => $res, 'nights' => $sum_night, 'user_info' => $user_info]);
        }
    }

}
