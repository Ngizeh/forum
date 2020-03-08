<?php


use Illuminate\Support\Facades\Route;


Route::get('/', 'ThreadsController@index');


Auth::routes();


Route::get('/home', 'HomeController@index')->name('home');



Route::get('/threads', 'ThreadsController@index')->name('threads');
Route::get('/threads/create', 'ThreadsController@create');
Route::get('/threads/{channel}/{thread}', 'ThreadsController@show');
Route::delete('/threads/{channel}/{thread}', 'ThreadsController@destroy');
Route::post('/threads', 'ThreadsController@store')->middleware('confirm-email');
Route::get('/threads/{channel}/{thread}/replies', 'RepliesController@index');
Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store');

Route::patch('/replies/{reply}', 'RepliesController@update');
Route::delete('/replies/{reply}', 'RepliesController@destroy');
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



