<?php

//https://github.com/codesleeve/asset-pipeline
//https://github.com/Reactive-Extensions/RxJS
//https://github.com/thujohn/twitter-l4

Route::get('/', array('as'=>'index', 'uses'=>'HomeController@index'));
Route::get('cloud', array('as'=>'friends-cloud', 'uses'=>'HomeController@cloud'));

Route::get('twitter/login',array('as'=>'twitter-login', 'uses'=>'TwitterController@login'));
Route::get('twitter/callback',array('as'=>'twitter-callback', 'uses'=>'TwitterController@callback'));

Route::get('twitter/friends',array('as'=>'twitter-friends', 'uses'=>'TwitterController@friends'));
Route::get('twitter/followers',array('as'=>'twitter-followers', 'uses'=>'TwitterController@followers'));
