<?php

namespace App\Http\Controllers\Transaksi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class ApproveController extends Controller
{
    public function index_order()
    {
        return view('pages.transaksi.approve.index_order');
    }

    public function table_order()
    {
        $data = DB::table('tbl_order')
            ->join('tbl_member', 'tbl_order.id_member', 'tbl_member.id_member')
            ->where('tbl_order.status', 0)
            ->get();
        return view('pages.transaksi.approve.tableorder', compact('data'));
    }

    public function approve_order(Request $req)
    {
        $id = $req->id_order;
        $status = $req->status;
        if ($status == 1) {
            // ambil data order
            $order = DB::table('tbl_order')->join('tbl_paket','tbl_order.jumlah_paket','tbl_paket.id_paket')->where('id_order', $id)->first();
            $updateorder = DB::table('tbl_order')
                ->where('id_order', $id)
                ->update([
                    'status' => $status
                ]);

            // ambil data member
            $member = DB::table('tbl_member')->where('id_member', $order->id_member)->first();
            if ($member->paket == 0) {
                $up = $order->jumlah;
            } else {
                $up = $order->jumlah + $member->paket;
            }
            $update = DB::table('tbl_member')
                ->where('id_member', $order->id_member)
                ->where('id_cabang', $order->id_cabang)
                ->update([
                    'paket' => $up
                ]);
        } else {
            $order = DB::table('tbl_order')->where('id_order', $id)->first();
            $updateorder = DB::table('tbl_order')
                ->where('id_order', $id)
                ->update([
                    'status' => $status
                ]);
        }
        return response()->json(['status' => 200, 'message' => 'Pemeriksaan Berhasil']);
    }
}
