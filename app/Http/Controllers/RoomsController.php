<?php

namespace App\Http\Controllers;

use App\Models\Images;
use App\Models\Rooms;
use App\Models\Settings;
use App\Models\Video;
use Illuminate\Http\Request;


class RoomsController extends Controller
{
    public function view(Request $request)
    {
        // Получение данных для отображения объекта
        $data = Rooms::find($request->id);// Получили данные по id объекта из БД rooms
        $photo = GetController::getImages($request->id);// Получили пути на фото

        // Получим все забронированные даты для отображения в календаре
        $date_b = DateController::getBookingDatesId($request->id);
        // Получим правила для календаря. Правила для календаря - строка вида 1&2&25.
        // Преобразуем её в массив по делиметру - &
        // 0 => Старт бронирования - 1 сегодня, 2 - завтра
        // 1 => Минимальное количество дней
        // 2 => Максимальное количество дней
        $res = Settings::get('rule');
        $rules = explode('&', $res [0]->rule);
        if (!empty($rules['2']) && $rules['2'] == 1) {
            $start = date("Y-m-d"); // Сегодняшняя дата
        } else {
            $d = strtotime("+1 day");
            $start = date("Y-m-d", $d); // автрашняя дата
        }
        $video = GetController::getPathVideoRoom($request->id); // Получили ссылку на видео объекта
        return view('rooms.view', ['video' => $video, 'start' => $start, 'data' => $data, 'photo' => $photo, 'date_book' => $date_b ['date_book'], 'rules' => $rules]);
    }

    public function editRoom(Request $request)
    {
        // этот код выполнится, если используется метод GET
        if ($request->isMethod('get')) {
            // Получение данных для редактирования объекта
            $room = Rooms::where('id', $request->id)->get();
            $photo_room = Images::where('room_id', $request->id)->get();
            $images = null;
            if (!empty(count($photo_room))) {
                foreach ($photo_room as $value) {
                    $images[] = $value->path;
                }
            }
            $video = GetController::getPathVideoRoom($request->id);// Получили ссылку на видео объекта
            return view('rooms.view_edit', ['video' => $video, 'data' => $room[0], 'images' => $images]);
        }

        // этот код выполнится, если используется метод POST
        if ($request->isMethod('post')) {
            // Изменение (редактирование) данных объекта в БД при использовании Ajax
            if (!empty($request->id)) {
                // Изменяем данные объекта в БВ по ID
                Rooms::where('id', $request->id)->update([
                    'address' => $request->address,
                    'price' => $request->price,
                    'title' => $request->title,
                    'text_room' => $request->text_room,
                    'capacity' => $request->capacity,
                    'service' => $request->service,
                ]);
                // Если указана ссылка на видео, то либо изменяем запись в БД, либо создаём
                if ($request->video !== '') {
                    // Проверяем на существовании видео объекта
                    $video = Video::where('room_id', $request->id)->get();
                    if (!empty(count($video))) {
                        Video::where('room_id', $request->id)->update(['path' => $request->video]);
                    } else {
                        Video::insert([
                            'room_id' => $request->id,
                            'path' => $request->video
                        ]);
                    }
                }
                $res = ['answer' => 'ok'];
            } else {
                $res = ['answer' => 'error'];
            }
        }
        exit(json_encode($res));
    }

    public function addRoom(Request $request)
    {
        // этот код выполнится, если используется метод GET
        if ($request->isMethod('get')) {
            return view('rooms.view_add');
        }

        // этот код выполнится, если используется метод POST
        if ($request->isMethod('post')) {
            // Добавление нового объекта в БД
            if (!empty($request->user_id)) {
                // Проверка обязательных полей
                $request->validate([
                    'name_room' => 'required|max:255',
                    'address' => 'required',
                    'price' => 'required',
                    'text_room' => 'required',

                ]);
                // Запись данных в БД
                $id = Rooms::insertGetId([
                    'name_room' => $request->name_room,
                    'user_id' => $request->user_id,
                    'address' => $request->address,
                    'price' => $request->price,
                    'text_room' => $request->text_room,
                    'capacity' => $request->capacity,
                    'service' => $request->service,
                    'video' => $request->video,
                    'coordinates' => $request->coordinates,

                ]);
                return redirect()->action('RoomsController@viewEdit', ['id' => $id]);
            }
        }
    }


}
