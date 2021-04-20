<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Cabang;
use Yajra\Datatables\Datatables;
use Validator;
use File;
use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CabangController extends Controller
{
    public function __construct()
    {
        $this->rules = array(
            'id_cabang' => 'numeric',
            'nama_cabang' => 'required|regex:/(^[A-Za-z0-9 .,]+$)+/',
            'lokasi' => 'required',
            'telepon' => 'required|numeric',
            'gambar_cabang' => 'mimes:jpeg,png,jpg,gif'
        );
    }

    public function index()
    {
        return view('pages.admin.cabang.index');
    }

    public function datatable()
    {
        return datatables()->of(DB::table('tbl_cabang')->get())->toJson();
    }

    public function add(Request $request)
    {

        $validation = Validator::make($request->all(), $this->rules);
        if ($validation->fails()) {
            return response()->json([
                'message'   => $validation->errors()->all(),
                'status' => 404
            ]);
        } else {
            $nama = $request->nama_cabang;
            $lokasi = $request->lokasi;
            $telepon = $request->telepon;
            $image = $request->file('gambar_cabang');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $new_name);
            $data = DB::table('tbl_cabang')->insert([
                'nama_cabang' => $nama,
                'lokasi' => $lokasi,
                'telepon' => $telepon,
                'gambar_cabang' => $new_name
            ]);
            return response()->json([
                'data' => $data,
                'message'   => 'Data Berhasil Diinput',
                'status' => 200
            ]);
        }
    }

    // public function dataBanner()
    // {
    //     $data = DB::table('tbl_banner')->get();
    //     return response()->json(['data' => $data])
    // }

    public function get(Request $request, $id = null)
    {
        try {
            if ($id) {
                $data = Cabang::findOrFail($id);
            } else {
                $data = Cabang::all();
            }
            return response()->json(['data' => $data, 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id_cabang;
        // dd($id);
        $edit = Cabang::findOrFail($id);
        if ($request->gambar_cabang != '') {
            $path = public_path() . '/images/';

            //code for remove old gambar_cabang
            if ($edit->gambar_cabang != ''  && $edit->gambar_cabang != null) {
                $file_old = $path . $edit->gambar_cabang;
                unlink($file_old);
            }

            //upload new file
            $file = $request->gambar_cabang;
            $filename = $file->getClientOriginalName();
            $file->move($path, $filename);

            //data yang lain
            $id_cabang = $request->id_cabang;
            $nama_cabang = $request->nama_cabang;
            $lokasi = $request->lokasi;
            $telepon = $request->telepon;

            //for update in table
            $data = DB::table('tbl_cabang')
                ->where('id_cabang', $id_cabang)
                ->update([
                    'nama_cabang' => $nama_cabang,
                    'lokasi' => $lokasi,
                    'telepon' => $telepon,
                    'gambar_cabang' => $filename
                ]);
            return redirect()->back()->with('status', 'Data Berhasil Diedit');
        } else {
            $id_cabang = $request->id_cabang;
            $nama_cabang = $request->nama_cabang;
            $lokasi = $request->lokasi;
            $telepon = $request->telepon;

            //for update in table
            $data = DB::table('tbl_cabang')
                ->where('id_cabang', $id_cabang)
                ->update([
                    'nama_cabang' => $nama_cabang,
                    'lokasi' => $lokasi,
                    'telepon' => $telepon
                ]);
            return redirect()->back()->with('status', 'Data Berhasil Diedit');
        }
        return redirect()->back()->with('error', 'Data Gagal Diedit');
    }

    public function remove(Request $request, $id)
    {
        try {
            $data = Cabang::findOrFail($id);
            $data->delete();
            return response()->json(['message' => 'Data Berhasil Di Hapus', 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }
}
