<?php

Route::get('/', 'HomeController@index');

Route::get('/twitter/login', 'TwitterController@login');
Route::get('/twitter/callback', 'TwitterController@callback');
