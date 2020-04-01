<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', 'ThreadsController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/threads', 'ThreadsController@index')->name('threads');
Route::get('/threads/create', 'ThreadsController@create')->middleware('confirm-email');
Route::get('/threads/{channel}/{thread}', 'ThreadsController@show');
Route::post('/threads', 'ThreadsController@store')->middleware('confirm-email')->name('threads');
Route::patch('/threads/{channel}/{thread}', 'ThreadsController@update');
Route::delete('/threads/{channel}/{thread}', 'ThreadsController@destroy')->name('thread.destroy');

Route::get('/threads/{channel}/{thread}/replies', 'RepliesController@index');
Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store');
Route::patch('/replies/{reply}', 'RepliesController@update');
Route::delete('/replies/{reply}', 'RepliesController@destroy')->name('reply.destroy');

Route::post('/lock-thread/{thread}', 'LockThreadController@store')->name('locked-thread.store')->middleware('admin');
Route::delete('/lock-thread/{thread}', 'LockThreadController@destroy')->name('locked-thread.destroy')->middleware('admin');


//Filtered by channel
Route::get('/threads/{channel}', 'ThreadsController@index');

Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@store')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@destroy')->middleware('auth');

Route::post('/replies/{reply}/favorites', 'FavoritesController@store');
Route::delete('/replies/{reply}/favorites', 'FavoritesController@destroy');

Route::post('replies/{reply}/best', 'BestReplyController@store')->name('best-reply.store');

Route::get('/profile/{user}', 'ProfilesController@show');
Route::get('/profile/{user}/notifications', 'UserNotificationController@index');
Route::delete('/profile/{user}/notifications/{notification}', 'UserNotificationController@destroy');


Route::get('/register/confirm', 'Auth\RegisterConfirmationController@index')->name('confirm');

Route::get('/api/users', 'Api\UsersController@index');
Route::post('/api/users/{user}/avatar', 'Api\UsersController@store');

Route::group([
   'prefix' => 'admin',
   'middleware' => 'admin',
   'namespace' => 'Admin'
],function () {
     Route::get('/', 'DashboardController@index')->name('admin.dashboard.index');
     Route::post('/channels', 'ChannelsController@store')->name('admin.channels.store');
     Route::get('/channels', 'ChannelsController@index')->name('admin.channels.index');
     Route::get('/channels/create', 'ChannelsController@create')->name('admin.channels.create');
     Route::patch('/channels/{channel}', 'ChannelsController@update');
     Route::delete('/channels/{channel}', 'ChannelsController@destroy')->name('admin.channels.destroy');
});




