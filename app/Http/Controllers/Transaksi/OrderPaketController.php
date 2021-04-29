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
            'jumlah_paket' => 'required|numeric',
            'tanggal_order' => 'required',
        );
    }

    public function index()
    {
        $data['member'] = DB::table('tbl_member')
            ->where('tbl_member.id_cabang', Auth::guard('pusat')->user()->id_cabang)
            ->get();
        $data['paket'] = DB::table('tbl_paket')->get();
        return view('pages.transaksi.order.index', $data);
    }

    public function pakets($id)
    {
        $data = DB::table('tbl_paket')->where('id_paket', $id)->first();
        echo json_encode($data);
    }

    public function table()
    {
        $id = Auth::guard('pusat')->user()->id_cabang;
        $data = DB::table('tbl_order')
            ->join('tbl_member', 'tbl_order.id_member', 'tbl_member.id_member')
            ->join('tbl_cabang', 'tbl_order.id_cabang', 'tbl_cabang.id_cabang')
            ->join('tbl_paket', 'tbl_order.jumlah_paket', 'tbl_paket.id_paket')
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
            $awal = $request->tanggal_order;
            $akhir = date('Y-m-d', strtotime('+30 days', strtotime($awal)));
            $user = Auth::guard('pusat')->user()->id_user;
            $cabang = Auth::guard('pusat')->user()->id_cabang;

            // cek tanggal order terakhir
            $today = date('Y-m-d');
            $cekexp = DB::table('tbl_order')->where('id_member', $request->id_member)->where('id_cabang', $cabang)->first();
            if($cekexp == true)
            {
                if($cekexp->tanggal_exp > $today)
                {
                    return response()->json(['message' => 'Masih Dalam Batas Pembelian', 'status' => 200]);
                } else {
                    $data = DB::table('tbl_order')->insert([
                        'id_user' => $user,
                        'id_member' => $request->id_member,
                        'tanggal_order' => $request->tanggal_order,
                        'tanggal_exp' => $akhir,
                        'jumlah_paket' => $request->jumlah_paket,
                        'id_cabang' => $cabang,
                        'status' => 0,
                    ]);
                }
                return response()->json(['message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
            } else {
                $data = DB::table('tbl_order')->insert([
                    'id_user' => $user,
                    'id_member' => $request->id_member,
                    'tanggal_order' => $request->tanggal_order,
                    'tanggal_exp' => $akhir,
                    'jumlah_paket' => $request->jumlah_paket,
                    'id_cabang' => $cabang,
                    'status' => 0,
                ]);
                return response()->json(['message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
            }
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
                        'jumlah_paket' => $request->jumlah_paket,
                        'tanggal_order' => $request->tanggal_order,
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

    // Api Android
    public function addPaket(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return response()->json(['messageForm' => $validator->errors(), 'status' => 422, 'message' => 'Data Tidak Valid']);
        } else {
            $awal = $request->tanggal_order;
            $akhir = date('Y-m-d', strtotime('+30 days', strtotime($awal)));
            $user = Auth::guard('member')->user()->id_member;
            $member = Auth::guard('member')->user()->id_member;

            // cek tanggal order terakhir
            $today = date('Y-m-d');
            $cekexp = DB::table('tbl_order')->where('id_member', $member)->where('id_cabang', $request->id_cabang)->first();
            if($cekexp == true)
            {
                if($cekexp->tanggal_exp > $today)
                {
                    return response()->json(['message' => 'Masih Dalam Batas Pembelian', 'status' => 404]);
                } else {
                    $data = DB::table('tbl_order')->insert([
                        'id_user' => $user,
                        'id_member' => $request->id_member,
                        'tanggal_order' => $request->tanggal_order,
                        'tanggal_exp' => $akhir,
                        'jumlah_paket' => $request->jumlah_paket,
                        'id_cabang' => $request->id_cabang,
                        'status' => 0,
                    ]);
                }
                return response()->json(['message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
            } else {
                $data = DB::table('tbl_order')->insert([
                    'id_user' => $user,
                    'id_member' => $request->id_member,
                    'tanggal_order' => $request->tanggal_order,
                    'tanggal_exp' => $akhir,
                    'jumlah_paket' => $request->jumlah_paket,
                    'id_cabang' => $request->id_cabang,
                    'status' => 0,
                ]);
                return response()->json(['message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
            }
        }
    }

    public function historyPaket(Request $request)
    {
        $id = Auth::guard('member')->user()->id_member;
        try {
            $data = DB::table('tbl_order')
                ->join('tbl_paket','tbl_order.jumlah_paket','tbl_paket.id_paket')
                ->join('tbl_cabang', 'tbl_order.id_cabang','tbl_cabang.id_cabang')
                ->select('tbl_order.*','tbl_paket.nama_paket','tbl_paket.harga','tbl_cabang.nama_cabang')
                ->where('tbl_order.id_member', $id)
                ->where('tbl_order.status' , 1)
                ->get();
            return response()->json(['data' => $data, 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function paketAktif(Request $request)
    {
        $id = Auth::guard('member')->user()->id_member;
        $today = date('Y-m-d');
        try {
            $data = DB::table('tbl_order')
                ->join('tbl_paket','tbl_order.jumlah_paket','tbl_paket.id_paket')
                ->join('tbl_cabang', 'tbl_order.id_cabang','tbl_cabang.id_cabang')
                ->select('tbl_order.*','tbl_paket.nama_paket','tbl_paket.harga','tbl_cabang.nama_cabang')
                ->where('tbl_order.id_member', $id)
                ->where('tbl_order.status' , 1)
                ->where('tbl_order.tanggal_exp', '>' , $today)
                ->get();
            return response()->json(['data' => $data, 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function paketCabang(Request $request, $id_cabang)
    {
        $id = Auth::guard('member')->user()->id_member;
        $id_cabang = $request->id_cabang;
        $today = date('Y-m-d');
        try {
            $data = DB::table('tbl_order')
                ->join('tbl_paket','tbl_order.jumlah_paket','tbl_paket.id_paket')
                ->join('tbl_cabang', 'tbl_order.id_cabang','tbl_cabang.id_cabang')
                ->select('tbl_order.*','tbl_paket.nama_paket','tbl_paket.harga','tbl_cabang.nama_cabang')
                ->where('tbl_order.id_member', $id)
                ->where('tbl_order.id_cabang', $id_cabang)
                ->where('tbl_order.status' , 1)
                ->where('tbl_order.tanggal_exp', '>' , $today)
                ->get();
            return response()->json(['data' => $data, 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }
}
