<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;


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


Route::get('/', 'FrontController@view')->name('front');

Route::post('/order_info', 'CalendarController@verification')->name('order.info');
Route::post('/add_booking', 'CalendarController@addBooking')->name('add.booking');
Route::get('/error_book', 'CalendarController@comeErrorBlade')->name('error.book');

Route::get('/reports_partner', 'PartnersController@view')->middleware('partner&admin')->name('reports.partner');

Route::get('/danke', 'DankeController@view');

Route::post('/search_room', 'SearchController@searchRoom')->name('search.room');

Route::get('/reports', 'ReportsController@view')->name('reports')->middleware('admin');
Route::get('/my_obj{id}', 'ReportsController@objView')->name('my.obj')->middleware('partner&admin');
Route::get('/paid{id}', 'ReportsController@paid')->name('paid')->middleware('admin');
Route::get('/room_reports{id}', 'ReportsController@roomReports')->name('room.reports')->middleware('partner&admin');
Route::get('/cancel_paid{id}', 'ReportsController@paidCancel')->name('paid.cancel')->middleware('admin');
Route::get('/paid.confirm{id}', 'ReportsController@paidConfirm')->name('paid.confirm')->middleware('partner&admin');

Route::post('/in_archive', 'ArhivController@inArchive')->name('in.archive')->middleware('admin');
Route::get('/view/{id}/archive', 'ArhivController@oneView')->name('view.archive')->middleware('admin');

Route::get('/orders', 'OrdersController@view')->name('orders')->middleware('admin');
Route::get('/order/{id}/confirm', 'OrdersController@confirm')->name('order.confirm')->middleware('admin');
Route::get('/order/{id}/reject', 'OrdersController@reject')->name('order.reject')->middleware('admin');
Route::get('/order/{id}/delete', 'OrdersController@delete')->name('order.delete')->middleware('admin');

Route::get('/schedule_clear', 'ClearController@clearSchedule')->name('schedule_clear')->middleware('admin');
Route::post('/add_calendar', 'CalendarController@setInfo')->name('add.calendar');

Route::get('/order/{id}/verification', 'VerificationController@verificationUserBook')->name('order.verification')->middleware('admin');

Route::get('/view/{id}/archive', 'ArhivController@oneView')->name('view.archive')->middleware('admin');


Route::get('/settings', 'SettingsController@view')->name('settings')->middleware('admin');

Route::match(['get', 'post'],'/front_edit', 'SettingsController@front')->name('front.edit')->middleware('admin');
Route::get('/rules_settings', 'RulesController@view')->name('rules_settings')->middleware('admin');
Route::post('/rules_edit', 'RulesController@edit')->name('rules_edit')->middleware('admin');

Route::get('/schedule', 'ScheduleController@view')->name('schedule')->middleware('admin');
Route::match(['get', 'post'],'/schedule_add', 'ScheduleController@add')->name('schedule.add')->middleware('admin');
Route::match(['get', 'post'],'/edit_schedule', 'ScheduleController@edit')->name('schedule.edit')->middleware('admin');

Route::post('/edit_table', 'ScheduleController@editTable')->name('edit.table')->middleware('admin');


Route::match(['get', 'post'],'/add_room', 'RoomsController@addRoom')->name('room')->middleware('admin');
Route::match(['get', 'post'],'/edit_room', 'RoomsController@editRoom')->name('edit.room')->middleware('admin');

Route::any('/upload_img/id{id}', 'FileController@uploadDrop')->name('upload.img')->middleware('admin');
Route::get('/delete_sess', 'FileController@deleteSess')->middleware('admin');
Route::any('/delete_img/room_id{id}', 'FileController@deleteDrop')->name('delete.img')->middleware('admin');

Route::get('/num{id}', 'RoomsController@view')->name('num.id');



Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return "Кэш очищен.";
})->name('clear');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
