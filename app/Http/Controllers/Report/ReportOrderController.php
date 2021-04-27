<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class ReportOrderController extends Controller
{
    public function index()
    {
        $data = DB::table('tbl_order')
            ->join('tbl_member', 'tbl_order.id_member','tbl_member.id_member')
            ->join('tbl_paket', 'tbl_order.jumlah_paket','tbl_paket.id_paket')
            ->join('tbl_cabang', 'tbl_member.id_cabang','tbl_cabang.id_cabang')
            ->select('tbl_order.*','tbl_member.nama_member','tbl_paket.nama_paket','tbl_paket.harga','tbl_cabang.nama_cabang')
            ->where('tbl_order.status', '=', 1)
            ->get();
        return view("pages.report.orderpaket.index", compact('data'));
    }
}
