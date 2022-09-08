<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidArgumentException;
use App\Mail\NewBooking;
use App\Mail\SendBooking;
use App\Mail\SendRegister;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

class CalendarController extends Controller
{
    public function view(Request $request)
    {
        if (!empty(session('date_book'))) {
            $request->session()->forget('date_book');
            $request->session()->save();
        }
        if (!empty(session('people'))) {
            $request->session()->forget('people');
            $request->session()->save();

        }

        $d = DB::table('rooms')->get();
        $data = json_decode(json_encode($d), true);

        $f = DB::table('settings')->find(1);
        $fr = json_decode(json_encode($f), true);
        $front = explode(',', $fr['front']);

        $r = DB::table('settings')->get('rule');
        $res = json_decode(json_encode($r), true);
        $rules = explode('&', $res [0]['rule']);
        if (!empty($rules['2']) && $rules ['2'] == 1) {
            $start = date("Y-m-d");
        } else {
            $d = strtotime("+1 day");
            $start = date("Y-m-d", $d);
        }
        return view('front')->with(['data' => $data, 'front' => $front, 'rules' => $rules, 'start' => $start]);

    }


    public function addBooking()
    {

        //Проверка на занятость дат
        $check = explode(',', $_POST['date_view']);
        for ($i = 0; $i < count($check) - 1; $i++) {
            $it = explode('/', $check[$i]);
            $array_dates[] = $it[0];
        }
        $all_dates = Booking::where('room', $_POST['id'])->get('date_book');
        if (!empty(count($all_dates))) {
            foreach ($all_dates as $da) {
                $array_table[] = $da->date_book;
            }
            if (!empty(count($array_dates))) {
                foreach ($array_dates as $ar) {
                    foreach ($array_table as $table) {
                        $tab = explode(',', $table);
                        if (in_array($ar, $tab)) {
                            return redirect()->action('CalendarController@comeErrorBlade');
                        }
                    }
                }
            }
        }
        // Конец проверки
        $user = explode(",", preg_replace('/\s+?\'\s+?/', '\'', $_POST ['more_book'] [0]));
        $name_user = $user[0];
        $user_info = implode(';', $_POST['more_book']);
        $users = User::get();
        $users_email = [];
        foreach ($users as $value) {
            $users_email [] = $value->email;
        }
        $email = preg_replace("/\s+/", "", $_POST['email_user']);
        if (empty(in_array($email, $users_email))) {
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $password = substr(str_shuffle($permitted_chars), 0, 16);
            User::insert([
                'name' => $name_user,
                'email' => $_POST ['email_user'],
                'password' => Hash::make($password),
            ]);
            $params = [
                'name_user' => $name_user,
                'email_user' => $_POST ['email_user'],
                'password' => $password,
            ];
            $subject2 = 'Регистрация на сайте';
            $toEmail2 = $_POST['email_user'];
            Mail::to($toEmail2)->send(new SendRegister($subject2, $params));
        }
        $code = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code_book = substr(str_shuffle($code), 0, 16);
        $d = $_POST ['date_book'];
        $d = preg_replace("/\s+/", "", $d);// удалили пробелы
        $dd = explode("-", $d);                           // преобразовали в массив
        $condition = 1;                                            // 1 - прибавить, 2 - вычесть
        DateController::setCountNightObj($dd, $_POST['id'], $_POST['sum'], $condition);
        $startTime = $dd[0];
        $endTime = $dd[1];
        $date_b = DateController::getDates($startTime, $endTime);
        $date_book = implode(',', $date_b);

        Booking::insert([
                'room' => $_POST['id'],
                'code_book' => $code_book,
                'name_user' => $name_user,
                'phone_user' => $_POST['phone_user'],
                'email_user' => $_POST['email_user'],
                'date_book' => $date_book,
                'no_in' => $startTime,
                'no_out' => $endTime,
                'more_book' => $_POST['date_view'],
                'user_info' => $user_info,
                'summ' => $_POST ['sum'],
            ]
        );

        $new_book = Booking::where('code_book', $code_book)->value('id');
        $data = [
            'in' => $startTime,
            'out' => $endTime,
            'name_user' => $name_user,
            'id' => $new_book,
            'url' => request()->root(),

        ];
        $subject = 'Бронирование дат';
        $toEmail = $_POST['email_user'];
        Mail::to($toEmail)->send(new SendBooking($subject, $data));
        $sub3 = 'Новое бронирование';
        $email_admin = '0120912@mail.ru';
        Mail::to($email_admin)->send(new NewBooking($sub3, $data));
        $mess = MessagesController::booking($email);
        return redirect()->action('DankeController@view', ['mess' => $mess]);
    }


    public function verification()
    {
        $date_view = [];
        $l = explode(',', $_POST ['date_view']);
        foreach ($l as $goo) {
            $date_view [] = $goo;
        }
        $date_view [] = $_POST ['summ'];
        $more_b = [];
        for ($li = 0; $li < count($_POST['name_user']); $li++) {
            $more_b[] = $_POST ['name_user'] [$li] . ", " . $_POST ['age'] [$li] . ", " . $_POST ['nationality'] [$li];
        }

        return view('verifications.verification_booking')->with(['date_view' => $date_view, 'more_book' => $more_b, 'id' => $_POST['id'], 'sum' => $_POST['sum']]);


    }

    public function addInfo(Request $request)
    {
        $request->validate([
            'date_book' => 'required'
        ]);
        $array_rooms = Schedule::where('room', $_POST['id'])->get();
        $array_date = [];
        foreach ($array_rooms as $it) {
            $array_date[] = $it->date_book;
        }
        $date_u = preg_replace("/\s+/", "", $_POST['date_book']);// удалили пробелы
        $date_u = explode("-", $date_u);
        $arr_date = DateController::getDates($date_u[0], $date_u[1]);
        $arr_date[]= $date_u[1];
        $sum_night = count($arr_date);
        $date_view = [];
        foreach ($arr_date as $item) {
            if (!empty(in_array($item, $array_date))) { // проверка есть ли в массиве
                $cumm_cost = Schedule::where('room', $_POST['id'])->where('date_book', $item)->value('cost');;
                $date_view[] = $item . "/" . $cumm_cost;
                $cost[] = $cumm_cost/* + $cost_arr*/
                ;
            } else {
                return view('sorry.sorry');
            }
        }
        $sum = array_sum($cost);
        $data = $_POST;
        return view('orders.order_info', ['data' => $data, 'date_view' => $date_view, 'sum' => $sum, 'sum_night' => $sum_night]);

    }

    public function comeErrorBlade()
    {
        return view('errors.error_book');
    }


}
