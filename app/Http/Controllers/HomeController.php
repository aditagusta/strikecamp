<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index_pusat()
    {
        return view('homepusat');
    }

    public function index_cabang()
    {
        return view('home');
    }
}
