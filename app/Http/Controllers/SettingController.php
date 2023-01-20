<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function view()
    {
        $data = Setting::all();
        $front = explode('&', $data[0]->front);
        if (empty($_GET['message'])) {
            $message = null;
        } else {
            $message = $_GET['message'];
        }
        return view('settings/settings_view', ['front' => $front, 'message' => $message]);
    }

    public function front(Request $request)
    {
        if ($request->isMethod('post')) {
            if (!empty($request->file('file'))) {
                //Загрузка фото
                $image = $request->file('file');
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $file = $request->file('file');
                $filename = substr(str_shuffle($permitted_chars), 0, 16) . '.' . $file->extension();
                $image->move(public_path('img/bg_image'), $filename);
                $data = [];
                for ($i = 0; $i < 3; $i++) {
                    $data[] = $request->data [$i];
                }
                $data[] = $filename;
                $front_str = implode('&', $data);
                Setting::where('id', 1)->update(['front' => $front_str]);
                $message = 'Настройки сохранены';
                return redirect()->action('SettingController@view', ['message' => $message]);
            } else {
                $data = implode('&', $_POST ['data']);
                Setting::where('id', 1)->update(['front' => $data]);
                $message = 'Настройки сохранены';
                return redirect()->action('SettingController@view', ['message' => $message]);
            }

        }
        if ($request->isMethod('get')) {
            $res = Setting::where('id', 1)->value('front');
            $data = explode('&', $res);
            return view('settings/front_settings', ['data' => $data]);
        }

    }


}
