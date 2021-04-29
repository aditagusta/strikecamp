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

    // Api Android
    public function loginMember(Request $request) {

        if ($token = Auth::guard('member')->attempt(["username" => $request->username, "password" => $request->password])) {
            return $this->respondWithToken($token);
        } else {
            return response()->json(['status' => 404,'message' => 'Cek Username dan Password']);
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'status' => 200,
            'message' => 'Berhasil Melakukan Login',
            "data" => Auth::guard('member')->user()
        ]);
    }
}
