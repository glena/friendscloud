@extends('layouts.master')

@section('content')

<h1>Friendscloud</h1>
<h2>Check your friends relations on twitter</h2>


@if ($userinfo)

    <h3>Welcome back!</h3>
    {{$userinfo->profile_banner_url}}<br>
    {{$userinfo->profile_link_color}}<br>
    {{$userinfo->profile_image_url}}<br>

    {{$userinfo->profile_background_image_url}}<br>
    {{$userinfo->profile_text_color}}<br>
    {{$userinfo->name}}<br>
    @ {{$userinfo->screen_name}}

    <a href="/cloud">Cloud</a>
@else
    <h3>Login with twitter to see your cloud</h3>
    <a href="/twitter/login">Login</a>
@endif





@stop