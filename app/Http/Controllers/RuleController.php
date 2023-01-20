<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RuleController extends Controller
{
    public function view()
    {
        $result = Setting::get('rule');// Получаем массив правил для календаря, где:
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
        Setting::where('id', 1)->update(['rule' => $rule]);
        $message = 'Настройки сохранены';
        return redirect()->action('SettingController@view', ['message' => $message]);

    }

}
