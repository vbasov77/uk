<?php

namespace App\Http\Controllers;


use App\Models\Image;

class ImagesController extends Controller
{
    public static function getDataWithPhoto($data)
    {
        // Добавляем первое фото для каждого объекта
        $photos = Image::all();
        foreach ($data as $item) {
            foreach ($photos as $photo) {
                if ($item->id == $photo->room_id) {
                    $item['path'] = $photo->path;
                    break;
                } else {
                    $item['path'] = null;
                }
            }
        }
        return $data;

    }


}
