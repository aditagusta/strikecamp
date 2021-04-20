<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Banner;
use Yajra\Datatables\Datatables;
use Validator;
use DB;
use File;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->rules = array(
            'id_banner' => 'numeric',
            'foto' => 'required|mimes:jpeg,png,jpg,gif',
        );
    }

    public function index()
    {
        return view('pages.admin.banner.index');
    }

    public function datatable()
    {
        return datatables()->of(Banner::all())->toJson();
    }

    function add(Request $request)
    {
        $validation = Validator::make($request->all(), $this->rules);
        if ($validation->fails()) {
            return response()->json([
                'message'   => $validation->errors()->all(),
                'status' => 404
            ]);
        } else {
            $image = $request->file('foto');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $new_name);
            $data = DB::table('tbl_banner')->insert([
                'foto' => $new_name
            ]);
            return response()->json([
                'data' => $data,
                'message'   => 'Data Berhasil Diinput',
                'status' => 200
            ]);
        }
    }

    public function remove(Request $request, $id)
    {
        try {
            $data = Banner::findOrFail($id);
            $destinationPath = 'images';
            File::delete($destinationPath . '/' . $data->foto);
            $data->delete();
            return response()->json(['message' => 'Data Berhasil Di Hapus', 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }
}
