@extends('layouts.master')

@section('content')

@stop

@section('inlineScripts')
<script>
    $(document).ready(function(){
        initCloud();
        loadFriends();
    });
</script>
@stop