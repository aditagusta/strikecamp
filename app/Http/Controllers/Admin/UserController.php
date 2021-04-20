<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use App\Model\User;
use Yajra\Datatables\Datatables;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    public function __construct()
    {
        $this->rules = array(
            'id_user' => 'numeric',
            'username' => 'required',
            'password' => 'required',
            'nama_user' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
            'telepon' => 'numeric',
            'level' => 'numeric',
            'id_cabang' => 'numeric',
        );
    }

    public function index()
    {
        $data = DB::table('tbl_cabang')->get();
        return view('pages.admin.user.index', compact('data'));
    }

    public function profilecabang()
    {
        $id = Auth::guard('pusat')->user()->id_user;
        // dd($id);
        $data = DB::table('tbl_user')
            ->join('tbl_cabang', 'tbl_user.id_cabang', 'tbl_cabang.id_cabang')
            ->where('id_user', $id)
            ->get();
        // dd($data);
        return view('pages.admin.profile.index', compact('data'));
    }

    public function datatable()
    {
        return datatables()->of(
            DB::table('tbl_user')
                ->join('tbl_cabang', 'tbl_user.id_cabang', 'tbl_cabang.id_cabang')
                ->select('tbl_user.*', 'tbl_cabang.nama_cabang')
                ->get()
        )->toJson();
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return response()->json(['messageForm' => $validator->errors(), 'status' => 422, 'message' => 'Data Tidak Valid']);
        } else {
            if ($request->password == $request->password1) {
                $pass = password_hash($request->password, PASSWORD_DEFAULT);
                $data = DB::table('tbl_user')->insert([
                    'username' => $request->username,
                    'password' => $pass,
                    'password1' => $request->password1,
                    'nama_user' => $request->nama_user,
                    'level' => 2,
                    'telepon' => $request->telepon,
                    'id_cabang' => $request->id_cabang,
                ]);
                return response()->json(['message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
            } else {
                return response()->json(['message' => 'Password Tidak Valid', 'status' => 200]);
            }
        }
    }

    public function get(Request $request, $id = null)
    {
        try {
            if ($id) {
                $data = User::findOrFail($id);
            } else {
                $data = User::all();
            }
            return response()->json(['data' => $data, 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id_user;
        try {
            $edit = User::findOrFail($id);
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                return response()->json(['status' => 422, 'message' => 'Data Tidak Valid']);
            } else {
                if ($request->password == $request->password1) {
                    $pass = password_hash($request->password, PASSWORD_DEFAULT);
                    $edit->username = $request->username;
                    $edit->password = $pass;
                    $edit->password1 = $request->password1;
                    $edit->nama_user = $request->nama_user;
                    $edit->telepon = $request->telepon;
                    $edit->id_cabang = $request->id_cabang;
                    $edit->save();
                    return response()->json(['message' => 'Data Berhasil Di Edit', 'data' => $edit, 'status' => 200]);
                } else {
                    return response()->json(['message' => 'Password Tidak Valid', 'status' => 200]);
                }
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function remove(Request $request, $id)
    {
        try {
            $data = User::findOrFail($id);
            $data->delete();
            return response()->json(['message' => 'Data Berhasil Di Hapus', 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }
}
