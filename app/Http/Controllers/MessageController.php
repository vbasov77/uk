<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    public static function booking(string $email)
    {
        $message = "Предварительное бронирование прошло успешно. <br> Ожидайте подтверждения администратором. На вашу почту, которую вы указали - <b>$email</b>, было отправлено письмо. Проверьте, пожалуйста, почту...<br> Если письма нет в папке 
'Входящие', проверьте папку 'Спам'. <div style='color: red'>Важно!!!</div> Если письмо поступило в папку 'Спам' и для того, чтобы письма в дальнейшем приходили в папку 'Входящие', и Вы получали оповещения, отметьте в ящике, что данное письмо не является спамом.<br>
Статус данного бронирования, вы сможете проверить в своём <a href='/profile'> Личном кабинете.</a>";
        return $message;
    }
}