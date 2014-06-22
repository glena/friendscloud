@extends('layouts.master')

@section('content')

{{$userinfo->profile_banner_url}}<br>
{{$userinfo->profile_link_color}}<br>
{{$userinfo->profile_image_url}}<br>
{{$userinfo->profile_use_background_image}}<br>
{{$userinfo->profile_background_image_url}}<br>
{{$userinfo->profile_text_color}}<br>
{{$userinfo->name}}<br>
@ {{$userinfo->screen_name}}


@stop