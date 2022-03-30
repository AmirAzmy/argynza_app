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

use App\Models\User\User;
use Carbon\Carbon;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/terms-and-conditions', function () {
    return view('termsAndConditions');
});

Route::get('/privacy', function () {
    return view('privacy');
});

//Route::get('/attendances', function () {
//    $users = User::whereHas('attendances', function ($attendance) {
//        $attendance->where('site_id', 1);
//    })
//        ->with([
//            'attendances' => function ($attendance) {
//                $attendance->whereMonth('day', 3);
//            }
//        ])
//        ->get();
//    return view('attendances', ["users" => $users]);
//});

Route::get('/admin/{any?}', function () {
    return view('index');
})->where('any', '^(?!(api|uploads).*$).*');

