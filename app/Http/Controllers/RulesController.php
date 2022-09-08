<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RulesController extends Controller
{
    public function view()
    {
        $result = Settings::get('rule');
        $rules = explode('&', $result [0]->rule);
        return view('settings.rules_settings', ['rules' => $rules]);
    }

    public function edit()
    {
        //$_POST['rules'] : массив  0 - старт дней, 1 - минимум дней, 2 - максимум дней
        $rule = implode('&', $_POST['rules']);
        Settings::where('id', 1)->update(['rule' => $rule]);
        $message = 'Настройки сохранены';
        return redirect()->action('SettingsController@view', ['message' => $message]);

    }

}
