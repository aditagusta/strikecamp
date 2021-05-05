<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Data Banner
Route::get('banner/datatable', 'Admin\BannerController@datatable');
Route::post('banner', 'Admin\BannerController@add');
Route::delete('banner/{id}', 'Admin\BannerController@remove');

// Data Katalog
Route::get('katalog/datatable', 'Admin\KatalogController@datatable');
Route::post('katalog', 'Admin\KatalogController@add');
Route::delete('katalog/{id}', 'Admin\KatalogController@remove');

// Data Cabang
Route::get('cabang/datatable', 'Admin\CabangController@datatable');
Route::post('cabang', 'Admin\CabangController@add');
Route::get('cabang/{id}', 'Admin\CabangController@get');
Route::delete('cabang/{id}', 'Admin\CabangController@remove');

// Data User
Route::post('user', 'Admin\UserController@add');
// Route::post('member', 'Admin\UserController@addmember');
Route::get('user/datatable', 'Admin\UserController@datatable');
Route::get('user/{id}', 'Admin\UserController@get');
Route::put('user', 'Admin\UserController@edit');
Route::delete('user/{id}', 'Admin\UserController@remove');

// Data Paket
Route::get('paket/datatable', 'Admin\PaketController@datatable');
Route::post('paket', 'Admin\PaketController@add');
Route::get('paket/{id}', 'Admin\PaketController@get');
Route::put('paket', 'Admin\PaketController@edit');
Route::delete('paket/{id}', 'Admin\PaketController@remove');

// Data Bank
Route::post('bank', 'Admin\BankController@add');
Route::get('bank/datatable/{id}', 'Admin\BankController@datatable');
Route::get('bank/{id}', 'Admin\BankController@get');
Route::put('bank', 'Admin\BankController@edit');
Route::delete('bank/{id}', 'Admin\BankController@remove');

// Data Trainer
Route::post('trainer', 'Admin\TrainerController@add');
Route::get('trainer/datatable/{id}', 'Admin\TrainerController@datatable');
Route::get('trainer/{id}', 'Admin\TrainerController@get');
Route::delete('trainer/{id}', 'Admin\TrainerController@remove');

// Data Jadwal
Route::post('schedule', "Admin\JadwalController@getjam");

// Data Member
//
Route::get('member/datatables', 'Admin\MemberController@datatables');
Route::post('members', 'Admin\MemberController@adds');
//
Route::post('member', 'Admin\MemberController@add');
Route::get('member/datatable/{id}', 'Admin\MemberController@datatable');
Route::get('member/{id}', 'Admin\MemberController@get');
Route::get('member/detail/{id}', 'Admin\MemberController@detail');
Route::delete('member/{id}', 'Admin\MemberController@remove');

// Order Paket
Route::post('order', 'Transaksi\OrderPaketController@add');
Route::get('order/{id}', 'Transaksi\OrderPaketController@get');
Route::put('order', 'Transaksi\OrderPaketController@edit');
Route::delete('order/{id}', 'Transaksi\OrderPaketController@remove');
Route::get('pakets/{id}', 'Transaksi\OrderPaketController@pakets');

// Booking
Route::post('cekjam', 'Transaksi\BookingController@cekjam');
Route::get('cekpaket/{id}', 'Transaksi\BookingController@cekpaket');

// Approve Order
Route::post('approve/order', 'Transaksi\ApproveController@approve_order');


// Api Android ===========================================================
// Register, Login
Route::post('register/member', 'Admin\MemberController@registerMember');
Route::post('login/member', 'LoginController@loginMember');

// Data Banner
Route::get('data/banner', 'Admin\BannerController@dataBanner');

// Data Katalog
Route::get('data/katalog', 'Admin\KatalogController@dataKatalog');

// Data Cabang
Route::get('info/cabang', 'Admin\CabangController@getInfo');

Route::group(["middleware" => "auth:member"], function() {
    // Data Member
    Route::get('info/member/{id}', 'Admin\MemberController@getInfo');
    // Order Paket
    Route::post('tambah/paket', 'Transaksi\OrderPaketController@addPaket');
    Route::get('info/history', 'Transaksi\OrderPaketController@historyPaket');
    Route::get('info/paket/aktif', 'Transaksi\OrderPaketController@paketAktif');
    Route::get('info/paket/cabang/{id_cabang}', 'Transaksi\OrderPaketController@paketCabang');
    // Jadwal
    Route::get('info/jadwal/{id}', 'Admin\JadwalController@getInfo');
    // Booking
    Route::post('tambah/booking', 'Transaksi\BookingController@addBooking');
    Route::get('info/booking', 'Transaksi\BookingController@historyBooking');
    // Data Paket
    Route::get('info/paket', 'Admin\PaketController@getInfo');
});
