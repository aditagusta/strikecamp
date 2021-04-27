<?php

namespace App\Http\Controllers\Transaksi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;

class BookingController extends Controller
{
    public function index()
    {
        $data['jadwal'] = DB::table('tbl_jadwal')
            ->join('tbl_trainer', 'tbl_jadwal.id_trainer', 'tbl_trainer.id_trainer')
            ->where('tbl_jadwal.id_cabang', Auth::guard('pusat')->user()->id_cabang)
            ->get();
        $data['member'] = DB::table('tbl_member')
            ->leftJoin('paket_member', 'tbl_member.id_member','paket_member.id_member')
            ->where('paket_member.sisa_paket', '!=' , '')
            ->where('tbl_member.id_cabang',Auth::guard('pusat')->user()->id_cabang)
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
                if($a->id_jam == 0)
                {
                    array_push($data['booking'],[
                        'id_booking' => $a->id_booking,
                        'id_user' => $a->id_user,
                        'id_member' => $a->id_member,
                        'id_jadwal' => $a->id_jadwal,
                        'id_jam' => $a->id_jam,
                        'jam' => 'Data Sudah di Ubah',
                        'id_cabang' => $a->id_cabang,
                        'nama_member' => $a->nama_member,
                        'jadwal' => $a->jadwal,
                        'nama_cabang' => $a->nama_cabang
                    ]);
                } else {
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
            }
        return view('pages.transaksi.booking.index', $data);
    }

    public function add(Request $req)
    {
        $id_jadwal = $req->tanggal;
        $id_jam = $req->jam;
        $id_member = $req->member;
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
        $exp = date('Y-m-d', strtotime('+30 days', strtotime($cekmember->tanggal_beli)));

        if($cekmember->sisa_paket < $hitung && $today > $exp)
        {
            return redirect("/booking")->with(['error', 'Paket Anda Tidak Cukup']);
        } else {
            if ($id_jam != null) {
                    $simpan = DB::table('tbl_booking')
                    ->insert([
                        'id_user' => $user,
                        'id_member' => $id_member,
                        'id_jadwal' => $id_jadwal,
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
                        ->select('sisa_paket','tanggal_beli')
                        ->orderBy('tanggal_beli', 'desc')
                        ->update(['sisa_paket' => $kurang]);
                }
            }
            return redirect("/booking")->with(['success', 'Data Booking Berhasil Ditambahkan']);
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

    public function cekjam(Request $req)
    {
        $data = DB::table('tbl_booking')
            ->select('id_jam')
            ->where('id_jadwal', $req->id_jadwal)
            ->get();
            // dd($data);
        if (count($data) > 0) {
            echo json_encode($data);
        } else {
            $data = '';
            echo json_encode($data);
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
                ->first();

            // tambahkah
            $tambah = $member->sisa_paket + $nilai;

            // update data member
            DB::table('paket_member')
            ->where('id_member', $data->id_member)
            ->where('id_cabang', $data->id_cabang)
            ->update([
                'sisa_paket' => $tambah
            ]);
            return redirect()->back()->with('status', 'Berhasil Dihapus');
        } else {
            return redirect()->back()->with('error', 'Data Gagal Dihapus');
        }
    }

    public function cekpaket($id)
    {
        $data = DB::table('paket_member')->where('id_member', $id)->orderBy('tanggal_beli', 'desc')->first();
        echo json_encode($data);
    }

    // Api Android
    public function addBooking(Request $request)
    {
        $id_member = Auth::guard('member')->user()->id_member;
        $id_jam = $request->jam;
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

        if($cekmember->sisa_paket < $hitung && $today > $exp)
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

                    // update table paket_member
                    $member = DB::table('paket_member')
                        ->where('id_cabang', $request->id_cabang)
                        ->where('id_member', $id_member)
                        ->select('sisa_paket','tanggal_beli')
                        ->orderBy('tanggal_beli', 'desc')
                        ->update(['sisa_paket' => $kurang]);
                }
                return response()->json(['status' => 200, 'message' => 'Booking Berhasil Dilakukan']);
            } else {
                return response()->json(['status' => 404, 'message' => 'Cek Pemilihan Jam Kembali']);
            }
        }
        return response()->json(['status' => 404, 'message' => 'Booking Gagal Dilakukan']);
    }
}
