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
Route::get('/get-more-album', 'LandingController@getNextAlbums');

// Login
Route::get('/login', 'AuthController@index')->middleware('checkAuth');
Route::get('/register', 'AuthController@index');
Route::post('/register', 'AuthController@createUser');
Route::post('/login', 'AuthController@login');
Route::post('/logout', 'AuthController@logout');

//Album
Route::get('/album/download', 'AlbumController@download');
Route::get('/album/{albumName}', 'AlbumController@index');

//Admin
Route::get('/dashboard', 'DashboardController@index')->middleware('checkAuth');
Route::get('/dashboard/get-song', 'DashboardController@showSong');
Route::post('/dashboard/store-album', 'DashboardController@storeAlbumInfo');
Route::post('/dashboard/store-song', 'DashboardController@storeSong');
Route::post('/dashboard/delete-song', 'DashboardController@deleteSong');
Route::get('/dashboard/get-album', 'DashboardController@showDetail');
Route::put('/dashboard/edit-album-info', 'DashboardController@updateAlbumInfo');
Route::post('/dashboard/edit-song', 'DashboardController@updateSong');
Route::post('/dashboard/edit-album-cover', 'DashboardController@updateAlbumCover');
Route::post('/dashboard/delete-album', 'DashboardController@deleteAlbum');



Route::get('/dashboard/album/{albumName}', 'AlbumDashboardController@index');
Route::delete('/dashboard/{$id}', 'DashboardController@destroy');

//query
Route::get('/query/get-album-name', 'DashboardController@getAlbumName');
Route::get('/query/get-recording-company', 'DashboardController@getRecordingCompany');
Route::get('/query/get-song-title', 'DashboardController@getSongTitle');
Route::get('/query/get-song-writer', 'DashboardController@getSongWriter');
Route::get('/query/get-singer', 'DashboardController@getSinger');
Route::get('/query/get-arranger', 'DashboardController@getArranger');
Route::get('/query/get-band-leader', 'DashboardController@getBandLeader');
Route::get('/query/get-band-name', 'DashboardController@getBandName');
Route::get('/query/get-matching-global-search', 'LandingController@getMatchingGlobalSearch');
Route::get('/query/get-clicked-category', 'LandingController@getClickedCategory');

//test
Route::get('/test', 'DashboardController@index');