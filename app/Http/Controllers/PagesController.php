<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    //
    public function root()
    {

        echo ini_get('memory_limit').PHP_EOL;

        return view('pages.root');
    }
}
