<?php

namespace App\Http\Controllers;


use App\Models\Rooms;
use App\Models\Settings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class FrontController extends Controller
{
    public function view(Request $request)
    {
        $request->session()->forget(['date_book', 'people']);// Удаляем сессию ('date_book', 'people') при обновлении
        $data = Rooms::all(); // Получили все объекты
        $objects = ImagesController::getDataWithPhoto($data);// Добавили фото к объектам
        $result = Settings::get(); // Получае правила настроек
        $rules = explode('&', $result[0]->rule); // Получаем массив правил для календаря, где:
        // 0 => (c какого дня разрешено бронировать: 1 - сегодня; 2 - завтра)
        // 1 => (минимальное количество дней)
        // 2 => (максимальное количество дней)

        // Формируем дату старта для календаря в формате: 2022-01-16
        if (!empty($rules[0]) && (int)$rules [0] == 1) {
            $start = date("Y-m-d");
        } else {
            $d = strtotime("+1 day");
            $start = date("Y-m-d", $d);
        }

        $front = explode('&', $result[0]->front);// Получили настройки главной страницы, где:
        //  0 => Заголовок
        //  1 => Цвет заголовка
        //  2 => Шрифт заголовка
        //  3 => Фото фона

        return view('front', ['data' => $objects, 'front' => $front, 'rules' => $rules, 'start' => $start]);
    }


}
