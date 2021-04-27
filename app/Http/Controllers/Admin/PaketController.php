<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Paket;
use Yajra\Datatables\Datatables;
use Validator;
use DB;
use File;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PaketController extends Controller
{
    public function __construct()
    {
        $this->rules = array(
            'nama_paket' => 'required',
            'jumlah' => 'required|numeric',
            'harga' => 'required|numeric',
        );
    }

    public function index()
    {
        return view('pages.admin.paket.index');
    }

    public function table()
    {
        $data = Paket::all();
        return view('pages.admin.paket.table', compact('data'));
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return response()->json(['messageForm' => $validator->errors(), 'status' => 422, 'message' => 'Data Tidak Valid']);
        } else {
            $data = DB::table('tbl_paket')->insert([
                'nama_paket' => $request->nama_paket,
                'jumlah' => $request->jumlah,
                'harga' => $request->harga,
            ]);
            return response()->json(['message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
        }
    }

    public function get(Request $request, $id = null)
    {
        try {
            if ($id) {
                $data = Paket::findOrFail($id);
            } else {
                $data = Paket::all();
            }
            return response()->json(['data' => $data, 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id_paket;
        try {
            $edit = Paket::findOrFail($id);
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                return response()->json(['status' => 422, 'message' => 'Data Tidak Valid']);
            } else {
                    $edit->nama_paket = $request->nama_paket;
                    $edit->jumlah = $request->jumlah;
                    $edit->harga = $request->harga;
                    $edit->save();
                    return response()->json(['message' => 'Data Berhasil Di Edit', 'data' => $edit, 'status' => 200]);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function remove(Request $request, $id)
    {
        try {
            $data = Paket::findOrFail($id);
            $data->delete();
            return response()->json(['message' => 'Data Berhasil Di Hapus', 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    // Api Android
    public function getInfo()
    {
        try {
            $data = DB::table('tbl_paket')->get();
            return response()->json(['data' => $data, 'status' => 200 , 'message'=> 'Data Ditemukan']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }
}
