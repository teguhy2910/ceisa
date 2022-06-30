<?php
use App\Http\Middleware\CheckLogin;
Auth::routes();
Route::group(['middleware' => [CheckLogin::class]], function () {
Route::get('/', 'MainController@index');
});
Route::get('/dashboard','MainController@dashboard');
Route::get('upload/iv/dashboard','MainController@upload_iv_dashboard');
Route::post('upload/iv/dashboard','MainController@upload_iv_dashboard_store');
Route::get('/terima_finance','MainController@terima_finance');
Route::post('/terima_finance','MainController@terima_finance_store');
Route::post('/update_fin_upload','MainController@update_fin_upload');
Route::post('/data_sj','MainController@data_sj');
Route::get('/report','MainController@report');
Route::post('/data_report','MainController@data_report');
Route::get('/report_pending','MainController@report_pending');
Route::post('/data_report_pending','MainController@data_report_pending');