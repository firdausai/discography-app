<?php

use Illuminate\Support\Facades\Route;

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


// Landing
Route::get('/', 'LandingController@index');

//Album
Route::get('/album/{albumName}', 'AlbumController@index');

//Admin
Route::get('/dashboard', 'DashboardController@index');
Route::get('/dashboard/get-song', 'DashboardController@showSong');
Route::post('/dashboard/store-album', 'DashboardController@storeAlbumInfo');
Route::post('/dashboard/store-song', 'DashboardController@storeSong');
Route::post('/dashboard/delete-song', 'DashboardController@deleteSong');
Route::get('/dashboard/get-album', 'DashboardController@showDetail');
Route::put('/dashboard/edit-album-info', 'DashboardController@updateAlbumInfo');
Route::post('/dashboard/edit-song', 'DashboardController@updateSong');
Route::post('/dashboard/edit-album-cover', 'DashboardController@updateAlbumCover');
Route::delete('/dashboard/{$id}', 'DashboardController@destroy');