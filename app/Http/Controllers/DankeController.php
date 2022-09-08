<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DankeController extends Controller
{
    public  function view()
    {
        return view('danke', ['mess' => $_GET ['mess']]);
    }

}
