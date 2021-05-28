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
            ->join('tbl_cabang', 'tbl_order.id_cabang','tbl_cabang.id_cabang')
            ->select('tbl_order.*','tbl_member.nama_member','tbl_paket.nama_paket','tbl_paket.harga','tbl_cabang.nama_cabang')
            ->where('tbl_order.status', '=', 1)
            ->get();
        return view("pages.report.orderpaket.index", compact('data'));
    }

    public function edit(Request $req)
    {
        $data = [];
        foreach ($req->id_order as $a) {
            $order = DB::table('tbl_order')
            ->join('tbl_member', 'tbl_order.id_member','tbl_member.id_member')
            ->join('tbl_paket', 'tbl_order.jumlah_paket','tbl_paket.id_paket')
            ->join('tbl_cabang', 'tbl_order.id_cabang','tbl_cabang.id_cabang')
            ->select('tbl_order.*','tbl_member.nama_member','tbl_paket.nama_paket','tbl_paket.harga','tbl_cabang.nama_cabang')
            ->where('tbl_order.id_order',$a)
            ->first();
            array_push($data,[
                'id_order' => $order->id_order,
                'tanggal_exp' => $order->tanggal_exp,
                'nama_member' => $order->nama_member,
                'nama_paket' => $order->nama_paket
            ]);
        }
        return response()->json(['data'=> $data]);
    }

    public function update(Request $r)
    {
        foreach ($r->id as $value) {
            $update = DB::table('tbl_order')->where('id_order',$value)->update(['tanggal_exp' => $r->tanggal]);
        }
        return back();
    }
}
