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
            ->where('id_cabang',Auth::guard('pusat')->user()->id_cabang)
            ->get();
        $data['booking'] = DB::table('tbl_booking')
            ->join('tbl_jadwal','tbl_booking.id_jadwal','tbl_jadwal.id_jadwal')
            // ->join('tbl_jam','tbl_booking.id_jam','tbl_jam.id_jam')
            ->join('tbl_member','tbl_booking.id_member','tbl_member.id_member')
            ->join('tbl_cabang','tbl_booking.id_cabang','tbl_cabang.id_cabang')
            ->select('tbl_booking.*','tbl_jadwal.jadwal','tbl_member.nama_member','tbl_cabang.nama_cabang')
            ->get();
        return view('pages.transaksi.booking.index', $data);
    }

    public function add(Request $req)
    {
        $id_jadwal = $req->tanggal;
        $id_jam = $req->jam;
        $id_member = $req->member;
        $hitung = count($id_jam);
        $jams = implode(",",$id_jam);

        $cekmember = DB::table('tbl_member')->where('id_member', $id_member)->select('paket')->first();
        if($cekmember->paket < $hitung)
        {
            return redirect("/booking")->with(['error', 'Paket Anda Tidak Cukup']);
        } else {
            if ($id_jam != null) {

                $user = Auth::guard('pusat')->user()->id_user;
                    $cabang = Auth::guard('pusat')->user()->id_cabang;
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
                    // ambil data member
                    $member = DB::table('tbl_member')->where('id_member', $id_member)->first();

                    // kurangkan paket yang ada
                    $kurang = $member->paket - $hitung;

                    // update table member
                    $member = DB::table('tbl_member')->where('id_member', $id_member)->update(['paket' => $kurang]);
                }
            }
            return redirect("/booking")->with(['success', 'Data Booking Berhasil Ditambahkan']);
        }
    }

    public function agenda(Request $req, $id)
    {
        $data['agenda'] = DB::table('tbl_agenda')
            ->join('tbl_jam', 'tbl_agenda.id_jam', 'tbl_jam.id_jam')
            ->where('tbl_agenda.id_jadwal', $id)
            ->get();
        return view('pages.transaksi.booking.agenda', $data);
    }

    public function cekjam(Request $req)
    {
        $data = DB::table('tbl_booking')
            ->select('id_jam')
            ->where('id_jadwal', $req->id_jadwal)
            ->get();
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
            $member = DB::table('tbl_member')
                ->where('id_member', $data->id_member)
                ->first();

            // tambahkah
            $tambah = $member->paket + $nilai;

            // update data member
            DB::table('tbl_member')
            ->where('id_member', $data->id_member)
            ->update([
                'paket' => $tambah
            ]);
            return redirect()->back()->with('status', 'Data operator berhasil ditambahkan');
        } else {
            return redirect()->back()->with('error', 'Data operator berhasil ditambahkan');
        }
    }

    public function cekpaket($id)
    {
        $data = DB::table('tbl_member')->where('id_member', $id)->first();
        echo json_encode($data);
    }
}
