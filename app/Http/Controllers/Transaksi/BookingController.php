<?php

namespace App\Http\Controllers\Transaksi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Session;
use Validator;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->rules = array(
            'id_jadwal' => 'required',
            'id_jam' => 'required',
        );

        $this->website = array(
            'tanggal' => 'required',
            'member' => 'required',
            'jam_' => 'required',
        );
    }

    public function index()
    {
        $today = date('Y-m-d');
        $data['jadwal'] = DB::table('tbl_jadwal')->get();
        $data['member'] = DB::table('tbl_order')
            ->join('tbl_member', 'tbl_order.id_member', 'tbl_member.id_member')
            ->select('tbl_order.*','tbl_member.nama_member')
            ->where('tbl_order.tanggal_exp', '>', $today)
            ->where('tbl_order.id_cabang',Auth::guard('pusat')->user()->id_cabang)
            ->get();

        $booking = DB::table('tbl_booking')
            ->join('tbl_jadwal','tbl_booking.id_jadwal','tbl_jadwal.id_jadwal')
            ->join('tbl_member','tbl_booking.id_member','tbl_member.id_member')
            ->join('tbl_cabang','tbl_booking.id_cabang','tbl_cabang.id_cabang')
            ->select('tbl_booking.*','tbl_jadwal.jadwal','tbl_member.nama_member','tbl_cabang.nama_cabang')
            ->where('tbl_booking.id_cabang',Auth::guard('pusat')->user()->id_cabang)
            ->get();
        $data['booking'] = [];
            foreach ($booking as $key => $a) {
                    $tes = explode(",",$a->id_jam);
                    foreach ($tes as $b) {
                        $datajam = DB::table('tbl_jam')->where('id_jam', $b)->first();
                        $jams [] = $datajam->jam;
                    }
                    array_push($data['booking'],[
                        'id_booking' => $a->id_booking,
                        'id_user' => $a->id_user,
                        'id_member' => $a->id_member,
                        'id_jadwal' => $a->id_jadwal,
                        'id_jam' => $a->id_jam,
                        'jam' => implode(",",$jams),
                        'id_cabang' => $a->id_cabang,
                        'nama_member' => $a->nama_member,
                        'jadwal' => $a->jadwal,
                        'nama_cabang' => $a->nama_cabang
                    ]);
                    $jams = [];
            }
        return view('pages.transaksi.booking.index', $data);
    }

    public function add(Request $req)
    {
        $validator = Validator::make($req->all(), $this->website);
        if ($validator->fails()) {
            return redirect("/booking");
        } else {
            $id_jadwal = $req->tanggal;
            $id_jam = $req->jam_;
            $id_member = $req->member;
            $id_trainer = $req->id_trainer;
            $hitung = count($id_jam);
            $jams = implode(",",$id_jam);
            $user = Auth::guard('pusat')->user()->id_user;
            $cabang = Auth::guard('pusat')->user()->id_cabang;
            $today = date('Y-m-d');

            $cekmember = DB::table('paket_member')
                ->where('id_cabang', $cabang)
                ->where('id_member', $id_member)
                ->select('sisa_paket','tanggal_beli')
                ->orderBy('tanggal_beli', 'desc')
                ->first();
            $cekbooking = DB::table('tbl_booking')
                ->where('id_member',$id_member)
                ->where('id_jadwal',$id_jadwal)
                ->first();
            $exp = date('Y-m-d', strtotime('+30 days', strtotime($cekmember->tanggal_beli)));

            if($cekmember->sisa_paket < $hitung || $today > $exp)
            {
                return redirect("/booking")->with('error', 'Paket Anda Tidak Cukup');
            } else {
                if($cekbooking == TRUE)
                {
                    return redirect("/booking")->with('error', 'Anda Sudah Memiliki Latihan');
                } else {
                    if ($id_jam != null) {
                        $simpan = DB::table('tbl_booking')
                        ->insert([
                            'id_user' => $user,
                            'id_member' => $id_member,
                            'id_jadwal' => $id_jadwal,
                            'id_trainer' => $id_trainer,
                            'id_jam' => $jams,
                            'id_cabang' => $cabang
                        ]);

                        if($simpan == true)
                        {
                            // kurangkan sisa paket
                            $kurang = $cekmember->sisa_paket - $hitung;
                            // update table paket_member
                            $member = DB::table('paket_member')
                                ->where('id_cabang', $cabang)
                                ->where('id_member', $id_member)
                                ->select('*')
                                ->orderBy('tanggal_beli', 'desc')
                                ->first();
                            $updatepaket = DB::table('paket_member')->whereIdPaketMember($member->id_paket_member)->update(['sisa_paket' => $kurang]);
                        }
                        return redirect("/booking")->with('status', 'Berhasil Menambahkan Booking Member');
                    } else {
                        return redirect("/booking")->with('error', 'Cek Kembali Inputan Anda');
                    }
                }
            }
        }
    }

    public function agenda(Request $req, $id)
    {
        $agenda = DB::table('tbl_jadwal')
            ->where('tbl_jadwal.id_jadwal', $id)
            ->get();
            $data['agenda'] =[];
            foreach ($agenda as $key => $a) {
                $tes = explode(",",$a->id_jam);
                // foreach ($tes as $b) {
                //     $datajam = DB::table('tbl_jam')->where('id_jam', $b)->first();
                //     $jams [] = $datajam->jam;
                // }
                array_push($data['agenda'],[
                    'id_jadwal' => $a->id_jadwal,
                    'id_user' => $a->id_user,
                    'jadwal' => $a->jadwal,
                    'id_cabang' => $a->id_cabang,
                    'id_trainer' => $a->id_trainer,
                    // 'jam' => implode(",",$jams),
                    'id_jam' => $tes,
                ]);
                // $jams = [];
            }
        return view('pages.transaksi.booking.agenda', $data);
    }

    public function cektrainer(Request $req)
    {
        $data = DB::table('jadwal_trainer')
            ->join('tbl_trainer','jadwal_trainer.id_trainer','tbl_trainer.id_trainer')
            ->where('id_jadwal', $req->id_jadwal)
            ->get();
        if (count($data) > 0) {
            echo json_encode($data);
        } else {
            $data = '';
            echo json_encode($data);
        }
    }

    public function cekjam(Request $request)
    {
        // dd($request->all());
        $data = DB::table('jadwal_jam')
            ->join('tbl_jam','jadwal_jam.id_jam','tbl_jam.id_jam')
            ->where('jadwal_jam.id_jadwal',$request["id_jadwal"])
            ->where('jadwal_jam.id_trainer',$request["id_trainer"])
            ->get();
        if($data == null){
            return response()->json(['status' => 404, 'message' => 'Data Tidak Ditemukan']);
        } else {
            return response()->json(['status' => 200, 'data' => $data, 'message' => 'Data Ditemukan']);
        }
    }

    public function remove(Request $req, $id)
    {
        $data = DB::table('tbl_booking')->where('id_booking', $id)->first();
        $value = explode(",",$data->id_jam);
        $nilai = count($value);

        $deleted['booking'] = DB::table('tbl_booking')->where('id_booking', $id)->delete();
        if ($deleted == TRUE) {
            // ambil data member
            $member = DB::table('paket_member')
                ->where('id_member', $data->id_member)
                ->where('id_cabang', $data->id_cabang)
                ->select('*')
                ->orderBy('tanggal_beli', 'desc')
                ->first();
                // tambahkah
            $tambah = $member->sisa_paket + $nilai;
            // update data member
            // DB::table('paket_member')
            // ->where('id_member', $data->id_member)
            // ->where('id_cabang', $data->id_cabang)
            // ->update([
            //     'sisa_paket' => $tambah
            // ]);
            $updatepaket = DB::table('paket_member')->whereIdPaketMember($member->id_paket_member)->update(['sisa_paket' => $tambah]);
            return redirect()->back()->with('status', 'Berhasil Dihapus');
        } else {
            return redirect()->back()->with('error', 'Data Gagal Dihapus');
        }
    }

    public function cekpaket($id)
    {
        $data = DB::table('paket_member')->where('id_member', $id)->where('id_cabang',Auth::guard('pusat')->user()->id_cabang)->orderBy('tanggal_beli', 'desc')->first();
        echo json_encode($data);
    }

    // Api Android
    public function addBooking(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return response()->json(['status' => 404, 'message' => 'Cek Inputan Anda Kembali']);
        } else {
            $id_member = Auth::guard('member')->user()->id_member;
            $id_jam = $request->id_jam;
            $hitung = count($id_jam);
            $jams = implode(",",$id_jam);
            $today = date('Y-m-d');

            $cekmember = DB::table('paket_member')
                ->where('id_cabang', $request->id_cabang)
                ->where('id_member', $id_member)
                ->select('sisa_paket','tanggal_beli')
                ->orderBy('tanggal_beli', 'desc')
                ->first();
            $exp = date('Y-m-d', strtotime('+30 days', strtotime($cekmember->tanggal_beli)));

            if($cekmember->sisa_paket < $hitung || $today > $exp)
            {
                return response()->json(['status' => 400, 'message' => 'Paket Atau Masa Berlaku Sudah Habis']);
            } else {
                if ($id_jam != null) {
                        $simpan = DB::table('tbl_booking')
                        ->insert([
                            'id_user' => $id_member,
                            'id_member' => $id_member,
                            'id_jadwal' => $request->id_jadwal,
                            'id_jam' => $jams,
                            'id_cabang' => $request->id_cabang
                        ]);

                    if($simpan == true)
                    {
                        // kurangkan sisa paket
                        $kurang = $cekmember->sisa_paket - $hitung;
                        $datamember = DB::table('tbl_member')
                            ->where('id_member', $id_member)
                            ->first();
                        // update table paket_member
                        $member = DB::table('paket_member')
                                ->where('id_cabang', $cabang)
                                ->where('id_member', $id_member)
                                ->select('*')
                                ->orderBy('tanggal_beli', 'desc')
                                ->first();
                        $updatepaket = DB::table('paket_member')->whereIdPaketMember($member->id_paket_member)->update(['sisa_paket' => $kurang]);
                    }
                    return response()->json(['status' => 200, 'message' => 'Booking Berhasil Dilakukan' , 'data' => $datamember,'sisa_paket' => $kurang]);
                } else {
                    return response()->json(['status' => 404, 'message' => 'Cek Pemilihan Jam Kembali']);
                }
            }
            return response()->json(['status' => 404, 'message' => 'Booking Gagal Dilakukan']);
        }
    }

    public function historyBooking()
    {
        $data = DB::table('tbl_booking')
        ->join('tbl_jadwal','tbl_booking.id_jadwal','tbl_jadwal.id_jadwal')
        ->join('tbl_cabang','tbl_booking.id_cabang','tbl_cabang.id_cabang')
        ->join('tbl_trainer','tbl_booking.id_trainer','tbl_trainer.id_trainer')
        ->select('tbl_booking.*','tbl_jadwal.jadwal','tbl_cabang.nama_cabang','tbl_cabang.lokasi','tbl_trainer.nama_trainer')
        ->get();
        $history = [];
        foreach ($data as $key => $a) {
            $ambilpaket = explode(",",$a->id_jam);
            $id_jam = count($ambilpaket);
            foreach ($ambilpaket as $b) {
                $datajam = DB::table('tbl_jam')->where('id_jam', $b)->select('jam')->first();
                $jams[] = $datajam->jam;
            }
            array_push($history,[
                'jadwal' => $a->jadwal,
                'lokasi' => $a->lokasi,
                'nama_cabang' => $a->nama_cabang,
                'latihan' => $id_jam,
                'jam' => implode(" ",$jams),
                'nama_trainer' => $a->nama_trainer
            ]);
            $jams =[];
        }
        if($data == TRUE)
        {
            return response()->json(['status' => 200 , 'message' => 'Data Anda Ditemukan', 'data' => $history]);
        } else {
            return response()->json(['status' => 404 , 'message' => 'Belum Pernah Melakukan Booking']);
        }
    }
}
