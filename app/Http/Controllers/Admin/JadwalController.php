<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
// use Alert;
use RealRashid\SweetAlert\Facades\Alert;
use App\Model\Jadwal;
use Validator;

class JadwalController extends Controller
{
    public function __construct()
    {
        $this->rules = array(
            'jadwal' => 'required',
        );

        $this->edit = array(
            'id_jadwal' => 'required',
            'jadwal' => 'required',
        );

        $this->trainers = array(
            'id_jadwal' => 'required',
            'id_trainer' => 'required',
        );

        $this->trainersedit = array(
            'id_jadwal_trainer' => 'required',
            'id_trainer' => 'required',
        );
    }

    public function index()
    {
        $data['jam'] = DB::table('tbl_jam')->get();
        $data['trainer'] = DB::table('tbl_trainer')->where('id_cabang', Auth::guard('pusat')->user()->id_cabang)->get();
        return view('pages.admin.jadwal.index', $data);
    }

    public function table(){
        $jadwal = DB::table('tbl_jadwal')->where('id_cabang',Auth::guard('pusat')->user()->id_cabang)->get();

        $data = [];
        $trainers = [];
        foreach ($jadwal as $key => $a) {
            $trainer = DB::table('jadwal_trainer')
                ->join('tbl_trainer','jadwal_trainer.id_trainer','tbl_trainer.id_trainer')
                ->where('id_jadwal', $a->id_jadwal)->get();
            // if($trainer == '')
            // {
            //     array_push($data,[
            //         'id_jadwal' => '',
            //         'jadwal' => '',
            //         'id_trainer' => ''
            //     ]);
            // } else {
                foreach ($trainer as $key => $tr) {
                    $trainers[] = $tr->id_trainer;
                }
                array_push($data,[
                    'id_jadwal' => $a->id_jadwal,
                    'jadwal' => $a->jadwal,
                    'id_trainer' => $trainers
                ]);
            // }
            $trainers = [];
        }
        return view('pages.admin.jadwal.table', compact('data'));
    }

    public function add(Request $r)
    {
        // $validator = Validator::make($r->all(), $this->rules);
        // if ($validator->fails()) {
        //     return redirect("/jadwal");
        // } else {
        //     $cektrainer = DB::table('tbl_jadwal')->where('id_trainer',$r->trainer)->where('jadwal',$r->jadwal)->first();
        //     // dd($cektrainer);
        //     if($cektrainer != null)
        //     {
        //         return redirect()->route("jadwal");
        //     } else {
        //         $jams = $r->jam;
        //         if($jams != null)
        //         {
        //             $id_jam = implode(",",$jams);
        //             $simpan = DB::table('tbl_jadwal')
        //                 ->insert([
        //                     'id_user' => $r->id_user,
        //                     'jadwal' => $r->jadwal,
        //                     'id_cabang' => $r->id_cabang,
        //                     'id_trainer' => $r->trainer,
        //                     'id_jam' => $id_jam
        //                 ]);
        //                 return redirect()->route("jadwal")->with('status', 'Data Berhasil Ditambahkan');
        //         } else {
        //             return redirect()->route("jadwal")->with('error', 'Data Gagal Ditambahkan');
        //         }
        //     }
        // }

        $validator = Validator::make($r->all(), $this->rules);
        $id_cabang = Auth::guard('pusat')->user()->id_cabang;
        if ($validator->fails()) {
            return response()->json(['status' => 404,'message' => 'Cek Inputan Anda']);
        } else {
            $cekjadwal = DB::table('tbl_jadwal')->where('jadwal', $r->jadwal)->where('id_cabang', $id_cabang)->first();
            if($cekjadwal != null)
            {
                return response()->json(['status' => 404,'message' => 'Data Sudah Ada']);
            } else {
                $simpan = DB::table('tbl_jadwal')->insert(['jadwal' => $r->jadwal,'id_cabang' => $id_cabang]);
                return response()->json(['status' => 200,'message' => 'Data Berhasil Ditambahkan']);
            }
        }
    }

    public function getjadwal(Request $r)
    {
        $data = DB::table('tbl_jadwal')
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
        $id_cabang = Auth::guard('pusat')->user()->id_cabang;
        $validator = Validator::make($req->all(), $this->edit);
        if ($validator->fails()) {
            return response()->json(['status' => 404,'message' => 'Cek Inputan Anda']);
        } else {
            $cekjadwal = DB::table('tbl_jadwal')->where('jadwal', $req->jadwal)->where('id_cabang', $id_cabang)->first();
            if($cekjadwal != null)
            {
                return response()->json(['status' => 404,'message' => 'Data Gagal Diubah']);
            } else {
                $ubah = DB::table('tbl_jadwal')->where('id_jadwal', $req->id_jadwal)->update(['jadwal' => $req->jadwal]);
                return response()->json(['status' => 200,'message' => 'Data Berhasil Diubah']);
            }
        }
    }

    public function addTrainer(Request $request)
    {
        $trainer = $request->id_trainer;
        $validator = Validator::make($request->all(), $this->trainers);
        if ($validator->fails()) {
            return response()->json(['status' => 404,'message' => 'Cek Inputan Anda']);
        } else {
            foreach ($trainer as $key => $a) {
                $cektrainer = DB::table('jadwal_trainer')->where('id_trainer', $a)->where('id_jadwal', $request->id_jadwal)->first();
                if($cektrainer != null)
                {
                    return response()->json(['status' => 404,'message' => 'Trainer Sudah Ada']);
                } else {
                $simpan = DB::table('jadwal_trainer')
                    ->insert([
                        'id_jadwal' => $request->id_jadwal,
                        'id_trainer' => $a
                    ]);
                }
            }
        }
        return response()->json(['status' => 200,'message' => 'Data Trainer Ditambahkan']);
    }

    public function cekTrainer(Request $r)
    {
        $data = DB::table('jadwal_trainer')->where('id_trainer', $r->id_trainer)->where('id_jadwal',$r->id_jadwal)->select('id_trainer')->get();
        if ($data == TRUE) {
            echo json_encode($data);
        } else {
            $data = '';
            echo json_encode($data);
        }
    }

    public function editTrainer(Request $r)
    {
        $trainer = $r->trainer;
        $cek = count($trainer);
        if($cek > 1)
        {
            return response()->json(['status' => 404,'message' => 'Pastikan Inputan Anda']);
        } else {
            $validator = Validator::make($r->all(), $this->trainersedit);
            if ($validator->fails()) {
                return response()->json(['status' => 404,'message' => 'Cek Inputan Anda']);
            } else {
                foreach ($trainer as $c) {
                    $cektrainer = DB::table('jadwal_trainer')->where('id_jadwal', $r->id_jadwal)->where('id_trainer', $c)->first();
                    if($cektrainer != null){
                        return response()->json(['status' => 404,'message' => 'Silahkan Pilih Trainer Lain']);
                    } else {
                        $data = DB::table('jadwal_trainer')->where('id_jadwal_trainer',$r->id_jadwal_trainer)->where('id_jadwal',$r->id_jadwal)->update(['id_trainer' => $c]);
                    }
                }
            }
            return response()->json(['status' => 200,'message' => 'Berhasil Mengubah Trainer']);
        }
    }

    public function addJam(Request $r)
    {
        $id_jam = $r->id_jam;
        if ($id_jam != null) {
            DB::table('jadwal_jam')
                ->where('id_jadwal', $r->id_jadwal)
                ->where('id_trainer', $r->id_trainer)
                ->delete();
            foreach ($id_jam as $no => $a)
            {
                $simpan = DB::table('jadwal_jam')
                    ->insert([
                        'id_jadwal' => $r->id_jadwal,
                        'id_trainer' => $r->id_trainer,
                        'id_jam' => $a,
                        'kapasitas' => 0
                    ]);
                if($simpan == TRUE)
                {
                    // hapus di tabel booking
                    $cekbooking = DB::table('tbl_booking')->where('id_jadwal', $r->id_jadwal)->get();
                    foreach ($cekbooking as $key => $c) {
                        $jamlatihan = explode(",",$c->id_jam);
                        $jumlah = count($jamlatihan);
                        // hapus di booking
                        $delbooking = DB::table('tbl_booking')->where('id_jadwal',$r->id_jadwal)->delete();
                        // ambil data di sisa paket
                        $member = DB::table('paket_member')
                            ->where('id_member', $c->id_member)
                            ->where('id_cabang',$c->id_cabang)
                            ->select('*')
                            ->orderBy('tanggal_beli', 'desc')
                            ->first();
                        // kembalikan paket tadi
                        $kembali = $member->sisa_paket + $jumlah;
                        $updatepaket = DB::table('paket_member')->where('id_paket_member',$member->id_paket_member)->update(['sisa_paket' => $kembali]);
                    }
                    // reset kapasitas
                    $reset = DB::table('jadwal_jam')->where('id_jadwal',$r->id_jadwal)->update(['kapasitas' => 0]);
                }
            }
        } else {
            DB::table('jadwal_jam')
            ->where('id_jadwal', $r->id_jadwal)
            ->where('id_trainer', $r->id_trainer)
            ->delete();
            return response()->json(['status' => 200,'message' => 'Data Jam Ditambahkan']);
        }
        return response()->json(['status' => 200,'message' => 'Data Jam Ditambahkan']);
    }

    public function remove(Request $req, $id)
    {
        $deleted['jadwal'] = DB::table('tbl_jadwal')->where('id_jadwal', $id)->delete();
        // cek booking
        if ($deleted == TRUE) {
            $trainer = DB::table('jadwal_trainer')->where('id_jadwal',$id)->delete();
            $jam = DB::table('jadwal_jam')->where('id_jadwal',$id)->delete();
            $cekbooking = DB::table('tbl_booking')->where('id_jadwal', $id)->get();
                foreach ($cekbooking as $key => $c) {
                    $jamlatihan = explode(",",$c->id_jam);
                    $jumlah = count($jamlatihan);
                    // hapus di booking
                    $delbooking = DB::table('tbl_booking')->where('id_jadwal',$id)->delete();
                    // ambil data di sisa paket
                    $member = DB::table('paket_member')
                        ->where('id_member', $c->id_member)
                        ->where('id_cabang',$c->id_cabang)
                        ->select('*')
                        ->orderBy('tanggal_beli', 'desc')
                        ->first();
                    // kembalikan paket tadi
                    $kembali = $member->sisa_paket + $jumlah;
                    $updatepaket = DB::table('paket_member')->where('id_paket_member',$member->id_paket_member)->update(['sisa_paket' => $kembali]);
            }
             return response()->json(['status' => 200,'message' => 'Data Berhasil Dihapus']);
        } else {
            return response()->json(['status' => 404,'message' => 'Data Gagal Dihapus']);
        }
    }

    public function removeTrainer(Request $request, $id)
    {
        $data = DB::table('jadwal_trainer')->where('id_jadwal_trainer',$id)->first();
        $delete = DB::table('jadwal_trainer')->where('id_jadwal_trainer',$id)->delete();
        if($delete == TRUE)
        {
            $deletejam = DB::table('jadwal_jam')->whereIdJadwal($data->id_jadwal)->whereIdTrainer($data->id_trainer)->delete();
            // hapus di tabel booking
            $cekbooking = DB::table('tbl_booking')->where('id_jadwal', $data->id_jadwal)->get();
                foreach ($cekbooking as $key => $c) {
                    $jamlatihan = explode(",",$c->id_jam);
                    $jumlah = count($jamlatihan);
                    // hapus di booking
                    $delbooking = DB::table('tbl_booking')->where('id_jadwal',$id)->delete();
                    // ambil data di sisa paket
                    $member = DB::table('paket_member')
                        ->where('id_member', $c->id_member)
                        ->where('id_cabang',$c->id_cabang)
                        ->select('*')
                        ->orderBy('tanggal_beli', 'desc')
                        ->first();
                    // kembalikan paket tadi
                    $kembali = $member->sisa_paket + $jumlah;
                    $updatepaket = DB::table('paket_member')->where('id_paket_member',$member->id_paket_member)->update(['sisa_paket' => $kembali]);
            }
            return response()->json(['status' => 200,'message' => 'Data Berhasil Dihapus']);
        } else {
            return response()->json(['status' => 404,'message' => 'Data Gagal Dihapus']);
        }
    }

    public function getJam(Request $request, $id_jadwal, $id_trainer)
    {
        $data = DB::table('jadwal_jam')->where('id_jadwal', $id_jadwal)->where('id_trainer', $id_trainer)->select('id_jam')->get();
        if($data != null){
            echo json_encode($data);
        } else {
            $data = '';
            echo json_encode($data);
        }
    }

    // Api android
    public function getInfo(Request $request,$id)
    {
        // $jadwal = DB::table('tbl_jadwal')->get();
        // $data = [];
        // $trainers = [];
        // $format = "%u %s";
        // foreach ($jadwal as $i => $a) {
        //     $trainer = DB::table('jadwal_trainer')
        //         ->join('tbl_trainer','jadwal_trainer.id_trainer','tbl_trainer.id_trainer')
        //         ->where('id_jadwal', $a->id_jadwal)->get();
        //     if($trainer == '')
        //     {
        //         array_push($data,[
        //             'id_jadwal' => '',
        //             'jadwal' => '',
        //             'id_trainer' => '',
        //             'nama_trainer' => '',
        //         ]);
        //     } else {
        //         foreach ($trainer as $b => $tr) {
        //             $trainers[] = $tr->id_trainer;
        //             $jam = DB::table('jadwal_jam')->where('id_jadwal', $a->id_jadwal)->get();
        //             foreach ($jam as $c) {
        //                 $jams[] = $c->id_jam;
        //             }
        //         }
        //         array_push($data,[
        //             'id_jadwal' => $a->id_jadwal,
        //             'jadwal' => $a->jadwal,
        //             'id_trainer' => $trainers,
        //             'id_jam' => $jams,
        //         ]);
        //     }
        //     $trainers = [];
        //     $jams = [];
        // }
        $data = DB::table('tbl_jadwal')->where('id_cabang',$id)->get();
        return response()->json(['status' => 200,'message' => 'Data Ditemukan', 'data'=>$data]);
    }

    public function listJadwal(Request $request,$id)
    {
        $data = DB::table('jadwal_jam')->join('tbl_jadwal','jadwal_jam.id_jadwal','tbl_jadwal.id_jadwal')->where('tbl_jadwal.id_jadwal',$id)->where('jadwal_jam.kapasitas','<','11')->get();
        if($data == TRUE)
        {
            return response()->json(['status' => 200,'message' => 'Data Ditemukan', 'data'=>$data]);
        } else {
            return response()->json(['status' => 404,'message' => 'Data Tidak Ditemukan']);
        }
    }

    public function getDataJam(Request $request)
    {
        $data = DB::table('tbl_jam')->get();
        if($data == TRUE){
            return response()->json(['status' => 200,'message' => 'Data Ditemukan', 'data'=>$data]);
        } else {
            return response()->json(['status' => 404,'message' => 'Data Tidak Ditemukan']);
        }
    }

    public function getDetailJam(Request $request,$id)
    {
        $data = DB::table('tbl_jam')->where('id_jam',$id)->first();
        if($data == TRUE){
            return response()->json(['status' => 200,'message' => 'Data Ditemukan', 'data'=>$data]);
        } else {
            return response()->json(['status' => 404,'message' => 'Data Tidak Ditemukan']);
        }
    }
}
