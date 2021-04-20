<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class JadwalController extends Controller
{
    public function index()
    {
        $data['jam'] = DB::table('tbl_jam')->get();
        $data['trainer'] = DB::table('tbl_trainer')->where('id_cabang', Auth::guard('pusat')->user()->id_cabang)->get();
        $data['jadwal'] = DB::table('tbl_jadwal')
            ->join('tbl_trainer', 'tbl_jadwal.id_trainer', 'tbl_trainer.id_trainer')
            ->where('tbl_jadwal.id_cabang', Auth::guard('pusat')->user()->id_cabang)
            ->get();
        return view('pages.admin.jadwal.index', $data);
    }

    public function add(Request $r)
    {
        $id_user = $r->id_user;
        $jadwal = $r->jadwal;
        $trainer = $r->trainer;
        $cabang = $r->id_cabang;
        // dd($cabang);
        $simpan = DB::table('tbl_jadwal')
            ->insert([
                'id_user' => $id_user,
                'jadwal' => $jadwal,
                'id_cabang' => $cabang,
                'id_trainer' => $trainer
            ]);
        if ($simpan == TRUE) {
            return redirect()->back()->with('status', 'Data Berhasil Ditambahkan');
        } else {
            return redirect()->back()->with('error', 'Data Gagal Ditambahkan');
        }
    }

    public function getjam(Request $r)
    {
        // dd($r->all());
        $data = DB::table('tbl_agenda')
            ->select('id_jam')
            ->where('id_jadwal', $r->id_jadwal)
            ->get();
        // dd($data);
        if (count($data) > 0) {
            echo json_encode($data);
        } else {
            $data = '';
            echo json_encode($data);
        }
    }

    public function addjam(Request $r)
    {
        $jadwal = $r->id_jadwal;
        $jam = $r->jam;

        if ($jam != null) {
            // dd($nama);
            DB::table('tbl_agenda')
                ->where('id_jadwal', $jadwal)
                ->delete();

            foreach ($jam as $no => $a) {
                $simpan = DB::statement("INSERT INTO `tbl_agenda`(`id_jadwal`, `id_jam`) VALUES ('$jadwal','$a')");
            }
        } else {
            DB::table('tbl_agenda')
                ->where('id_jadwal', $jadwal)
                ->delete();
            return redirect()->back()->with('status', 'Data operator berhasil ditambahkan');
        }
        return redirect()->back()->with('status', 'Data operator berhasil ditambahkan');
    }

    public function edit(Request $req)
    {
        $edited = DB::table('tbl_jadwal')
            ->where('id_jadwal', $req->id_jadwal)
            ->update([
                'jadwal' => $req->jadwal_,
                'id_trainer' => $req->trainer_
            ]);
        if ($edited == TRUE) {
            return redirect()->back()->with('status', 'Data operator berhasil ditambahkan');
        } else {
            return redirect()->back()->with('error', 'Data operator berhasil ditambahkan');
        }
    }

    public function remove(Request $req, $id)
    {
        $deleted['jadwal'] = DB::table('tbl_jadwal')->where('id_jadwal', $id)->delete();
        $deleted['agenda'] = DB::table('tbl_agenda')->where('id_jadwal', $id)->delete();
        if ($deleted == TRUE) {
            return redirect()->back()->with('status', 'Data operator berhasil ditambahkan');
        } else {
            return redirect()->back()->with('error', 'Data operator berhasil ditambahkan');
        }
    }
}
