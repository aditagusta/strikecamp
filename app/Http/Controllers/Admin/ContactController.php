<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class ContactController extends Controller
{
    public function index()
    {
        $data = DB::table('tbl_cabang')->get();
        return view('pages.admin.contact.index', compact('data'));
    }
}
