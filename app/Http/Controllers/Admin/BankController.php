<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Model\Bank;
use Yajra\Datatables\Datatables;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BankController extends Controller
{
    public function __construct()
    {
        $this->rules = array(
            'nama_bank' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
            'rekening' => 'numeric',
            'id_cabang' => 'numeric',
        );
    }

    public function index()
    {
        return view('pages.admin.bank.index');
    }

    public function datatable($id_cabang)
    {
        return datatables()->of(DB::table('tbl_bank')->where('id_cabang', $id_cabang)->get())->toJson();
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => 'Data Tidak Valid']);
        } else {
            return response()->json(['id' => Bank::create($request->all())->id_bank, 'message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
        }
    }

    public function get(Request $request, $id = null)
    {
        try {
            if ($id) {
                $data = Bank::findOrFail($id);
            } else {
                $data = Bank::all();
            }
            return response()->json(['data' => $data, 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id_bank;
        try {
            $edit = Bank::findOrFail($id);
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                return response()->json(['messageForm' => $validator->errors(), 'status' => 422, 'message' => 'Data Tidak Valid']);
            } else {
                $edit->id_bank = $request->id_bank;
                $edit->nama_bank = $request->nama_bank;
                $edit->rekening = $request->rekening;
                $edit->id_cabang = $request->id_cabang;
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
            $data = Bank::findOrFail($id);
            $data->delete();
            return response()->json(['message' => 'Data Berhasil Di Hapus', 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }
}
