<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
// use Alert;
use RealRashid\SweetAlert\Facades\Alert;
use App\Model\Jadwal;

class JadwalController extends Controller
{
    public function index()
    {
        $data['jam'] = DB::table('tbl_jam')->get();
        $data['trainer'] = DB::table('tbl_trainer')->where('id_cabang', Auth::guard('pusat')->user()->id_cabang)->get();
        $jadwal = DB::table('tbl_jadwal')
        ->join('tbl_trainer', 'tbl_jadwal.id_trainer', 'tbl_trainer.id_trainer')
        ->select('tbl_jadwal.*','tbl_trainer.nama_trainer')
        ->where('tbl_jadwal.id_cabang', Auth::guard('pusat')->user()->id_cabang)
        ->get();
        $data['jadwal'] = [];
        foreach ($jadwal as $key => $a) {
            $tes = explode(",",$a->id_jam);
            foreach ($tes as $b) {
                $datajam = DB::table('tbl_jam')->where('id_jam', $b)->first();
                $jams[] = $datajam->jam;
            }
            array_push($data['jadwal'],[
                'id_jadwal' => $a->id_jadwal,
                'id_jam' => $tes,
                'jadwal' => $a->jadwal,
                'jam' => implode(" ",$jams),
                'nama_trainer' => $a->nama_trainer,
                'id_trainer' => $a->id_trainer,
            ]);
            $jams = [];
        }
        return view('pages.admin.jadwal.index', $data);
    }

    public function add(Request $r)
    {
        $jams = $r->jam;
        if($jams != null)
        {
            $id_jam = implode(",",$jams);
            $simpan = DB::table('tbl_jadwal')
                ->insert([
                    'id_user' => $r->id_user,
                    'jadwal' => $r->jadwal,
                    'id_cabang' => $r->id_cabang,
                    'id_trainer' => $r->trainer,
                    'id_jam' => $id_jam
                ]);

                // $r->session()->put("status", "Berhasil Menambah Jadwal");
                return redirect()->route("jadwal");
        } else {
            // $r->session()->put("status", "Gagal Menambah Jadwal Periksa Inputan");
            return redirect()->route("jadwal");
        }
    }

    public function getjam(Request $r)
    {
        $data = DB::table('tbl_jadwal')
            ->select('id_jam','id_jadwal')
            ->where('id_jadwal', $r->id_jadwal)
            ->get();
        if ($data != null) {
            echo json_encode($data);
        } else {
            $data = '';
            echo json_encode($data);
        }
    }

    public function edit(Request $req)
    {
        $jam = $req->jam;
        if($jam != null)
        {
            $id_jam = implode(",",$jam);
            $edited = DB::table('tbl_jadwal')
                ->where('id_jadwal', $req->id_jadwal)
                ->update([
                    'jadwal' => $req->jadwal_,
                    'id_trainer' => $req->trainer_,
                    'id_jam' => $id_jam
                ]);
                if($edited == TRUE)
                {
                    // cek booking
                    $cekbooking = DB::table('tbl_booking')->where('id_jadwal', $req->id_jadwal)->get();
                    // dd($cekbooking);
                        foreach ($cekbooking as $key => $c) {
                            $jamlatihan = explode(",",$c->id_jam);
                            $jumlah = count($jamlatihan);
                            // hapus di booking
                            $delbooking = DB::table('tbl_booking')->where('id_jadwal',$req->id_jadwal)->delete();
                            // ambil data di sisa paket
                            $paket_member = DB::table('paket_member')
                                ->where('id_member', $c->id_member)
                                ->where('id_cabang',$c->id_cabang)
                                ->first();
                            $kembali = $paket_member->sisa_paket + $jumlah;
                            // kembalikan paket tadi
                            $uppaket = DB::table('paket_member')
                            ->where('id_member', $c->id_member)
                            ->where('id_cabang',$c->id_cabang)
                            ->update(['sisa_paket' => $kembali]);
                        }
                }
                // $req->session()->put("status", "Data Jadwal Berhasil Diubah");
                return redirect()->route("jadwal");
        } else {
            // $req->session()->put("status", "Gagal Merubah Jadwal");
            return redirect()->route("jadwal");
        }
    }

    public function remove(Request $req, $id)
    {
        $deleted['jadwal'] = DB::table('tbl_jadwal')->where('id_jadwal', $id)->delete();
        if ($deleted == TRUE) {
                // $req->session()->put("status", "Berhasil Menghapus Jadwal");
                return redirect()->route("jadwal");
        } else {
            // $req->session()->put("status", "Gagal Menghapus Jadwal");
            return redirect()->route("jadwal");
        }
    }

    // Api android
    public function getInfo($id)
    {
        $jadwal = DB::table('tbl_jadwal')
        ->join('tbl_trainer', 'tbl_jadwal.id_trainer', 'tbl_trainer.id_trainer')
        ->select('tbl_jadwal.*','tbl_trainer.nama_trainer')
        ->where('tbl_jadwal.id_cabang', $id)
        ->get();
        $data['jadwal'] = [];
        foreach ($jadwal as $key => $a) {
            $tes = explode(",",$a->id_jam);
            foreach ($tes as $b) {
                $datajam = DB::table('tbl_jam')->where('id_jam', $b)->first();
                $jams[] = $datajam->jam;
            }
            array_push($data['jadwal'],[
                'id_jadwal' => $a->id_jadwal,
                'id_jam' => $tes,
                'jadwal' => $a->jadwal,
                'jam' => implode(" ",$jams),
                'nama_trainer' => $a->nama_trainer,
                'id_trainer' => $a->id_trainer,
            ]);
            $jams = [];
        }
        return response()->json(['status' => 200,'message' => 'Data Ditemukan', 'data'=>$data['jadwal']]);
    }
}
