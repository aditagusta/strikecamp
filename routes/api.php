<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login/member', 'LoginController@loginMember');

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
Route::post('member', 'Admin\UserController@addmember');
Route::get('user/datatable', 'Admin\UserController@datatable');
Route::get('user/{id}', 'Admin\UserController@get');
Route::put('user', 'Admin\UserController@edit');
Route::delete('user/{id}', 'Admin\UserController@remove');

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

// Data Schedule
Route::post('schedule', "Admin\JadwalController@getjam");

// Data Member
Route::post('member', 'Admin\MemberController@add');
Route::get('member/datatable/{id}', 'Admin\MemberController@datatable');
Route::get('member/{id}', 'Admin\MemberController@get');
Route::delete('member/{id}', 'Admin\MemberController@remove');

// Order Paket
Route::post('order', 'Transaksi\OrderPaketController@add');
Route::get('order/{id}', 'Transaksi\OrderPaketController@get');
Route::put('order', 'Transaksi\OrderPaketController@edit');
Route::delete('order/{id}', 'Transaksi\OrderPaketController@remove');

// Booking
Route::post('cekjam', 'Transaksi\BookingController@cekjam');
Route::get('cekpaket/{id}', 'Transaksi\BookingController@cekpaket');

// Approve Order
Route::post('approve/order', 'Transaksi\ApproveController@approve_order');

// Api Android
Route::get('jadwalapi', 'Transaksi\OrderPaketController@jadwal');
