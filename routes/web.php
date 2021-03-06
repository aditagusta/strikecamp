<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
        return view('pages.login');
});

Route::get('login', 'LoginController@index');
Route::post('/login', 'LoginController@login2')->name('postlogin');

Route::group(['middleware' => 'auth:pusat','cekstatus:1,2'], function () {
    // Home
    Route::get('/homepusat', 'HomeController@index_pusat')->name('homepusat');
    Route::get('/home', 'HomeController@index_cabang')->name('home');

    Route::get('/logout', 'LoginController@logout2')->name('logout');

    // Data Banner
    Route::get('/banner', 'Admin\BannerController@index')->name('banner');

    // Data Katalog
    Route::get('/katalog', 'Admin\KatalogController@index')->name('katalog');

    // Data Cabang
    Route::get('/cabang', 'Admin\CabangController@index')->name('cabang');
    Route::put('/cabang/edit', 'Admin\CabangController@edit')->name('editcabang');

    // Data Paket
    Route::get('/paket','Admin\PaketController@index')->name('paket');
    Route::get('/table/paket','Admin\PaketController@table')->name('tablepaket');

    // Data User
    Route::get('/user', 'Admin\UserController@index')->name('user');

    // Contact Cabang
    Route::get('/contact', 'Admin\ContactController@index')->name('contact');

    // Laporan Order
    Route::get('/laporan/order', 'Report\ReportOrderController@index');
    Route::post('/edit/tanggal', 'Report\ReportOrderController@update');

    // Data Bank
    Route::get('/bank', 'Admin\BankController@index')->name('bank');

    // Profile Cabang
    Route::get('/profile/cabang', 'Admin\UserController@profilecabang')->name('profilecabang');

    // Data Trainer
    Route::get('/trainer', 'Admin\TrainerController@index')->name('trainer');
    Route::put('/trainer/edit', 'Admin\TrainerController@edit')->name('edittrainer');

    // Data Jadwal
    Route::get('/jadwal', 'Admin\JadwalController@index')->name('jadwal');
    Route::get('/table/jadwal', 'Admin\JadwalController@table')->name('tablejadwal');

    // Data Member
    Route::get('/member', 'Admin\MemberController@index')->name('member');
    Route::get('/members', 'Admin\MemberController@index_pusat')->name('members');
    Route::put('/member/edit', 'Admin\MemberController@edit')->name('editmember');
    Route::put('/members/edit', 'Admin\MemberController@edits')->name('editmembers');

    // Data Order Paket
    Route::get('/order', 'Transaksi\OrderPaketController@index')->name('order_paket');
    Route::get('/order/table', 'Transaksi\OrderPaketController@table')->name('tableorder');

    // Data Booking Pelatihan
    Route::get('/booking', 'Transaksi\BookingController@index')->name('booking');
    Route::get('/table/agenda/{id?}', 'Transaksi\BookingController@agenda')->name('tableagenda');
    Route::post('/addbooking', 'Transaksi\BookingController@add')->name('addbooking');
    Route::delete('/booking/{id?}', 'Transaksi\BookingController@remove')->name('removebooking');

    // Approve Order
    Route::get('/approve', 'Transaksi\ApproveController@index_order')->name('approve_order');
    Route::get('/approve/table/order', 'Transaksi\ApproveController@table_order')->name('table_order_approve');
});

// Route::get('test', function(){
//     $jadwal = \App\Model\Jadwal::find(92);

//     return $jadwal->trainers->first()->jam;
// });
