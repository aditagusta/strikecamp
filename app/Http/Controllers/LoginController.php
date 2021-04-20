<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('pages.login');
    }

    // public function login(Request $request)
    // {
    //     $this->validate($request, [
    //         'username' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
    //         'password' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
    //     ]);
    //     $username = $request->username;
    //     $password = $request->password;
    //     $data = DB::table('tbl_user')->where('username', $username)->first();
    //     // dd($data);
    //     if ($data == null) {
    //         return back()->with('pesan', 'Username Atau Password Salah!');
    //     } else {
    //         $cek = Hash::check($password, $data->password);
    //         // dd($cek);
    //         if ($cek == true) {
    //             $request->session()->put("id", $data->id_user);
    //             $request->session()->put("nama", $data->nama_user);
    //             $request->session()->put("level", $data->level);
    //             $request->session()->put("cabang", $data->id_cabang);
    //             if ($data->level == "1") {
    //                 return redirect('/homepusat');
    //             }
    //             elseif ($data->level == "2") {
    //                 return redirect('/home');
    //             }
    //         } else {
    //             return back()->with('pesan', 'Username Atau Password Salah!');
    //         }
    //     }
    // }


    function login2(Request $request){
        $this->validate($request, [
            'username' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
            'password' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
        ]);
        $credentials = Auth::guard('pusat')->attempt(['username' => $request->username, 'password' => $request->password]);
        if ($credentials){
            if(Auth::guard('pusat')->user()->level == 1)
            {
                return redirect()->route('homepusat');
            }else{
                return redirect()->route('home');
            }
        } else {
            return redirect('login');
        }
    }

    function logout2(){
        Auth::guard('pusat')->logout();
        return redirect('/login');
    }

    // public function logout(Request $req)
    // {
    //     $req->session()->forget('id');
    //     $req->session()->forget('nama');
    //     $req->session()->forget('level');
    //     $req->session()->forget('cabang');
    //     return redirect("/login");
    // }

    // Login Member /  Konsumen
    public function loginMember(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
            'password' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
        ]);
        $username = $request->username;
        $password = $request->password;
        $data = DB::table('tbl_member')->where('username', $username)->first();
        // dd($data);
        if ($data == null) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        } else {
            $cek = Hash::check($password, $data->password);
            // dd($cek);
            if ($cek == true) {
                $request->session()->put("id", $data->id_member);
                $request->session()->put("nama", $data->nama_member);
                $request->session()->put("cabang", $data->id_cabang);
                return response()->json(['status' => 200, 'message' => 'Berhasil Melakukan Login']);
            } else {
                return response()->json(['message' => 'Password Salah', 'status' => 404]);
            }
        }
    }
}
