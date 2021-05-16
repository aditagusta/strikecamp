<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Trainer;
use Yajra\Datatables\Datatables;
use Validator;
use DB;
use File;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TrainerController extends Controller
{
    public function __construct()
    {
        $this->rules = array(
            'id_trainer' => 'numeric',
            'nama_trainer' => 'required',
            'foto_trainer' => 'mimes:jpeg,png,jpg,gif',
        );
    }

    public function index()
    {
        return view('pages.admin.trainer.index');
    }

    public function datatable($id)
    {
        return datatables()->of(DB::table('tbl_trainer')->where('id_cabang', $id)->get())->toJson();
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
            $nama = $request->nama_trainer;
            $id_cabang = $request->id_cabang;
            $image = $request->file('foto_trainer');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $new_name);
            $data = DB::table('tbl_trainer')->insert([
                'nama_trainer' => $nama,
                'id_cabang' => $id_cabang,
                'foto_trainer' => $new_name
            ]);
            return response()->json([
                'data' => $data,
                'message'   => 'Data Berhasil Diinput',
                'status' => 200
            ]);
        }
    }

    public function get(Request $request, $id = null)
    {
        try {
            if ($id) {
                $data = Trainer::findOrFail($id);
            } else {
                $data = Trainer::all();
            }
            return response()->json(['data' => $data, 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id_trainer;
        $edit = Trainer::findOrFail($id);
        if ($request->foto_trainer != '') {
            $path = public_path() . '/images/';

            //code for remove old foto_trainer
            if ($edit->foto_trainer != ''  && $edit->foto_trainer != null) {
                $file_old = $path . $edit->foto_trainer;
                unlink($file_old);
            }

            //upload new file
            $file = $request->foto_trainer;
            $filename = $file->getClientOriginalName();
            $file->move($path, $filename);

            //data yang lain
            $id_trainer = $request->id_trainer;
            $nama_trainer = $request->nama_trainer;

            //for update in table
            $data = DB::table('tbl_trainer')
                ->where('id_trainer', $id_trainer)
                ->update([
                    'nama_trainer' => $nama_trainer,
                    'foto_trainer' => $filename
                ]);
            return redirect()->back()->with('status', 'Data Berhasil Diedit');
        } else {
            $id_trainer = $request->id_trainer;
            $nama_trainer = $request->nama_trainer;

            //for update in table
            $data = DB::table('tbl_trainer')
                ->where('id_trainer', $id_trainer)
                ->update([
                    'nama_trainer' => $nama_trainer,
                ]);
            return redirect()->back()->with('status', 'Data Berhasil Diedit');
        }
        return redirect()->back()->with('error', 'Data Gagal Diedit');
    }

    public function remove(Request $request, $id)
    {
        try {
            $data = Trainer::findOrFail($id);
            $destinationPath = 'images';
            File::delete($destinationPath . '/' . $data->foto_trainer);
            $data->delete();
            return response()->json(['message' => 'Data Berhasil Di Hapus', 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function jadwal(){
        $data = DB::table('tbl_jadwal')->get();
    }

    // Api Android
    public function getInfo()
    {
        $data = DB::table('tbl_trainer')->get();
        if($data == TRUE)
        {
            return response()->json(['message' => 'Data Ditemukan', 'status' => 200,'data' => $data]);
        } else {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function getDetailTrainer(Request $request,$id)
    {
        $data = DB::table('tbl_trainer')->where('id_trainer',$id)->first();
        if($data == TRUE)
        {
            return response()->json(['message' => 'Data Ditemukan', 'status' => 200,'data' => $data]);
        } else {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }
}
