<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Model\Member;
use Yajra\Datatables\Datatables;
use Validator;
use File;
use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->rules = array(
            'id_member' => 'numeric',
            'username' => 'required',
            'password' => 'required',
            'nama_member' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
            'telepon' => 'numeric',
            'id_cabang' => 'numeric',
            'gambar_member' => 'required|mimes:jpeg,png,jpg,gif',
        );
    }

    public function index()
    {
        return view('pages.admin.member.index');
    }

    public function index_pusat()
    {
        $data['cabang'] = DB::table('tbl_cabang')->get();
        return view('pages.admin.member.index_pusat', $data);
    }

    public function datatable($id)
    {
        return datatables()->of(
            DB::table('tbl_member')->where('id_cabang', $id)->get()
        )->toJson();
    }

    public function datatables()
    {
        return datatables()->of(
            DB::table('tbl_member')->join('tbl_cabang','tbl_member.id_cabang','tbl_cabang.id_cabang')->get()
        )->toJson();
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return response()->json(['messageForm' => $validator->errors(), 'status' => 422, 'message' => 'Data Tidak Valid']);
        } else {
            $image = $request->file('gambar_member');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $new_name);
            if ($request->password == $request->password1) {
                $pass = password_hash($request->password, PASSWORD_DEFAULT);
                $data = DB::table('tbl_member')->insert([
                    'username' => $request->username,
                    'password' => $pass,
                    'password1' => $request->password1,
                    'nama_member' => $request->nama_member,
                    'telepon' => $request->telepon,
                    'id_cabang' => $request->id_cabang,
                    'gambar_member' => $new_name,
                ]);
                return response()->json(['message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
            } else {
                return response()->json(['message' => 'Password Tidak Valid', 'status' => 200]);
            }
        }
    }

    public function adds(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return response()->json(['messageForm' => $validator->errors(), 'status' => 422, 'message' => 'Data Tidak Valid']);
        } else {
            $image = $request->file('gambar_member');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $new_name);
            if ($request->password == $request->password1) {
                $pass = password_hash($request->password, PASSWORD_DEFAULT);
                $data = DB::table('tbl_member')->insert([
                    'username' => $request->username,
                    'password' => $pass,
                    'password1' => $request->password1,
                    'nama_member' => $request->nama_member,
                    'telepon' => $request->telepon,
                    'id_cabang' => $request->id_cabang,
                    'gambar_member' => $new_name,
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
                $data = Member::findOrFail($id);
            } else {
                $data = Member::all();
            }
            return response()->json(['data' => $data, 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function detail(Request $request, $id = null)
    {
        try {
            if ($id) {
                $data = Member::findOrFail($id);
            } else {
                $data = Member::all();
            }
            return response()->json(['data' => $data, 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id_member;
        $edit = Member::findOrFail($id);
        if ($request->gambar_ != '') {
            $path = public_path() . '/images/';

            //code for remove old gambar_
            if ($edit->gambar_ != ''  && $edit->gambar_ != null) {
                $file_old = $path . $edit->gambar_;
                unlink($file_old);
            }

            //upload new file
            $file = $request->gambar_;
            $filename = $file->getClientOriginalName();
            $file->move($path, $filename);

            //data yang lain
            $id_member = $request->id_member;
            $username = $request->username;
            $pass = password_hash($request->password, PASSWORD_DEFAULT);
            $password1 = $request->password1;
            $nama_member = $request->nama_member;
            $telepon = $request->telepon;

            //for update in table
            $data = DB::table('tbl_member')
                ->where('id_member', $id_member)
                ->update([
                    'username' => $username,
                    'password' => $pass,
                    'password1' => $password1,
                    'nama_member' => $nama_member,
                    'gambar_member' => $filename,
                    'telepon' => $telepon,
                ]);
            return redirect()->back()->with('status', 'Data Berhasil Diedit');
        } else {
            $id_member = $request->id_member;
            $username = $request->username;
            $pass = password_hash($request->password, PASSWORD_DEFAULT);
            $password1 = $request->password1;
            $nama_member = $request->nama_member;
            $telepon = $request->telepon;

            //for update in table
            $data = DB::table('tbl_member')
                ->where('id_member', $id_member)
                ->update([
                    'username' => $username,
                    'password' => $pass,
                    'password1' => $password1,
                    'nama_member' => $nama_member,
                    'telepon' => $telepon,
                ]);
            return redirect()->back()->with('status', 'Data Berhasil Diedit');
        }
        return redirect()->back()->with('error', 'Data Gagal Diedit');
    }

    public function edits(Request $request)
    {
        $id = $request->id_member;
        $edit = Member::findOrFail($id);
        if ($request->gambar_ != '') {
            $path = public_path() . '/images/';

            //code for remove old gambar_
            if ($edit->gambar_ != ''  && $edit->gambar_ != null) {
                $file_old = $path . $edit->gambar_;
                unlink($file_old);
            }

            //upload new file
            $file = $request->gambar_;
            $filename = $file->getClientOriginalName();
            $file->move($path, $filename);

            //data yang lain
            $id_member = $request->id_member;
            $username = $request->username;
            $pass = password_hash($request->password, PASSWORD_DEFAULT);
            $password1 = $request->password1;
            $nama_member = $request->nama_member;
            $telepon = $request->telepon;
            $id_cabang = $request->id_cabang;

            //for update in table
            $data = DB::table('tbl_member')
                ->where('id_member', $id_member)
                ->update([
                    'username' => $username,
                    'password' => $pass,
                    'password1' => $password1,
                    'nama_member' => $nama_member,
                    'gambar_member' => $filename,
                    'telepon' => $telepon,
                    'id_cabang' => $id_cabang,
                ]);
            return redirect()->back()->with('status', 'Data Berhasil Diedit');
        } else {
            $id_member = $request->id_member;
            $username = $request->username;
            $pass = password_hash($request->password, PASSWORD_DEFAULT);
            $password1 = $request->password1;
            $nama_member = $request->nama_member;
            $telepon = $request->telepon;
            $id_cabang = $request->id_cabang;

            //for update in table
            $data = DB::table('tbl_member')
                ->where('id_member', $id_member)
                ->update([
                    'username' => $username,
                    'password' => $pass,
                    'password1' => $password1,
                    'nama_member' => $nama_member,
                    'telepon' => $telepon,
                    'id_cabang' => $id_cabang,
                ]);
            return redirect()->back()->with('status', 'Data Berhasil Diedit');
        }
        return redirect()->back()->with('error', 'Data Gagal Diedit');
    }

    public function remove(Request $request, $id)
    {
        try {
            $data = Member::findOrFail($id);
            $destinationPath = 'images';
            File::delete($destinationPath . '/' . $data->gambar_member);
            $data->delete();
            return response()->json(['message' => 'Data Berhasil Di Hapus', 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    // Api Android
    public function registerMember(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return response()->json(['messageForm' => $validator->errors(), 'status' => 422, 'message' => 'Data Tidak Valid']);
        } else {
            $image = $request->file('gambar_member');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $new_name);
            if ($request->password == $request->password1) {
                $pass = password_hash($request->password, PASSWORD_DEFAULT);
                $data = DB::table('tbl_member')->insert([
                    'username' => $request->username,
                    'password' => $pass,
                    'password1' => $request->password1,
                    'nama_member' => $request->nama_member,
                    'telepon' => $request->telepon,
                    'id_cabang' => $request->id_cabang,
                    'gambar_member' => $new_name,
                ]);
                return response()->json(['message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
            } else {
                return response()->json(['message' => 'Password Tidak Valid', 'status' => 200]);
            }
        }
    }

    public function getInfo()
    {
        $id = Auth::guard('member')->user()->id_member;
        try {
            // if ($id) {
            //     $data = Member::findOrFail($id);
            // } else {
            //     $data = Member::all();
            // }
            $data = DB::table('tbl_member')
                // ->leftJoin('paket_member', 'tbl_member.id_member','paket_member.id_member')
                // ->select('tbl_member.*', 'paket_member.sisa_paket')
                ->where('tbl_member.id_member', $id)
                ->get();
            return response()->json(['data' => $data, 'status' => 200 , 'message'=> 'Data Ditemukan']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }
}
