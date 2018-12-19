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

/*
Route::get('/', function () {
    return view('welcome');
});
*/

//認証周りを有効にする
Auth::routes();


//ログイン
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
//Route::match(['get','post'],'login', 'Auth\LoginController@login')->name('login');
//Route::get('logout', 'Auth\LoginController@logout')->name('logout');


Route::group(['middleware' => 'auth'], function ()
{
  //TOP
  Route::get('/', 'HomeController@index');

  //店舗管理
  Route::resource("shop", "ShopController");

  //テスト用テーブル管理
  Route::resource("reporttesttable", "ReporttesttableController");

  //テストメールアドレス追加
  Route::post("reporttesttable/testmailaddress_add/", "ReporttesttableController@testmailaddress_add");

  //テストメールアドレス削除
  Route::get("reporttesttable/testmailaddress_del/{id}", "ReporttesttableController@testmailaddress_del");
  //CSVインポート
  Route::resource("csvimport", "CsvimportController");

  //画像管理
  Route::resource("image", "ImageController");
});

/*
Route::group(['prefix' => 'auth','middleware' => 'auth'], function () {
  Route::get('home', 'Auth\LoginController@index')->name('AdminHome');
});

Route::group(['prefix' => 'auth'], function () {
  Route::match(['get','post'],'login', 'Auth\LoginController@login')->name('login');
  Route::get('logout', 'Auth\LoginController@logout')->name('logout');
});
*/



