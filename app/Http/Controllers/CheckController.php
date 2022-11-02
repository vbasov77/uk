<?php

namespace App\Http\Controllers;

use App\Mail\SendRegister;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class CheckController extends Controller
{
    public static function checkEmailEndRegister(string $user, string $email){
        $users = User::get('email'); // Получили емаилы всех юзеров
        $users_email = [];
        // Формируем числовой массив из почт юзеров
        foreach ($users as $value) {
            $users_email [] = $value->email;
        }

        // Проверка юзера по емаил, если его нет в БД, то регистрируем его
        if (empty(in_array($email, $users_email))) {
            // Допустимые символы для формирования пароля
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            // Формируем пароль
            $password = substr(str_shuffle($permitted_chars), 0, 16);
            // Записываем нового юзера в БД
            User::insert([
                'name' => $user,
                'email' => $email,
                'password' => Hash::make($password),
            ]);
            //Формируем данные для письма на почту юзеру о регистрации
            $params = [
                'name_user' => $user,
                'email_user' => $email,
                'password' => $password,
            ];
            $subject = 'Регистрация на сайте'; // Заголовок письма
            $to_email = $email;
            Mail::to($to_email)->send(new SendRegister($subject, $params)); // Отправили письмо
        }
    }

}
