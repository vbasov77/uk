<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RulesController extends Controller
{
    public function view()
    {
        $result = Settings::get('rule');// Получаем массив правил для календаря, где:
        // 0 => (c какого дня разрешено бронировать: 1 - сегодня; 2 - завтра)
        // 1 => (минимальное количество дней)
        // 2 => (максимальное количество дней)
        $rules = explode('&', $result [0]->rule);
        return view('settings.rules_settings', ['rules' => $rules]);
    }

    public function edit(Request $request)
    {
        // Изменение правил календаря в БД
        //$request->rules : массив
        // 0 => (c какого дня разрешено бронировать: 1 - сегодня; 2 - завтра)
        // 1 => (минимальное количество дней)
        // 2 => (максимальное количество дней)
        $rule = implode('&', $request->rules);
        Settings::where('id', 1)->update(['rule' => $rule]);
        $message = 'Настройки сохранены';
        return redirect()->action('SettingsController@view', ['message' => $message]);

    }

}
