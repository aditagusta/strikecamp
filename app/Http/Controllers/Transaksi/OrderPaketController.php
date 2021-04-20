<?php

namespace App\Http\Controllers\Transaksi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use App\Model\OrderPaket;
use Yajra\Datatables\Datatables;
use Validator;
use File;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderPaketController extends Controller
{
    public function __construct()
    {
        $this->rules = array(
            'id_member' => 'required|numeric',
            'jumlah' => 'required|numeric',
            'tanggal' => 'required',
        );
    }

    public function index()
    {
        $data['member'] = DB::table('tbl_member')->where('id_cabang', Auth::guard('pusat')->user()->id_cabang)->get();
        $data['paket'] = DB::table('tbl_paket')->get();
        return view('pages.transaksi.order.index', $data);
    }

    public function table()
    {
        $id = Auth::guard('pusat')->user()->id_cabang;
        $data = DB::table('tbl_order')
            ->join('tbl_member', 'tbl_order.id_member', 'tbl_member.id_member')
            ->where('tbl_order.status', 0)
            ->where('tbl_order.id_cabang', $id)
            ->get();
        return view('pages.transaksi.order.table', compact('data'));
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return response()->json(['messageForm' => $validator->errors(), 'status' => 422, 'message' => 'Data Tidak Valid']);
        } else {
            $user = Auth::guard('pusat')->user()->id_user;
            $cabang = Auth::guard('pusat')->user()->id_cabang;
            $data = DB::table('tbl_order')->insert([
                'id_user' => $user,
                'id_member' => $request->id_member,
                'tanggal_order' => $request->tanggal,
                'jumlah_paket' => $request->jumlah,
                'id_cabang' => $cabang,
                'status' => 0,
            ]);
            return response()->json(['message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
        }
    }

    public function get(Request $request, $id = null)
    {
        try {
            if ($id) {
                $data = OrderPaket::findOrFail($id);
            } else {
                $data = OrderPaket::all();
            }
            return response()->json(['data' => $data, 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id_order;
        try {
            $edit = OrderPaket::findOrFail($id);
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                return response()->json(['status' => 422, 'message' => 'Data Tidak Valid']);
            } else {
                $data = DB::table('tbl_order')
                    ->where('id_order', $request->id_order)
                    ->update([
                        'id_user' => $request->id_user,
                        'id_member' => $request->id_member,
                        'jumlah_paket' => $request->jumlah,
                        'tanggal_order' => $request->tanggal,
                        'status' => $request->status,
                        'id_cabang' => $request->id_cabang,
                    ]);
                return response()->json(['message' => 'Data Berhasil Di Edit', 'status' => 200]);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function remove(Request $request, $id)
    {
        try {
            $data = OrderPaket::findOrFail($id);
            $data->delete();
            return response()->json(['message' => 'Data Berhasil Di Hapus', 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }
}