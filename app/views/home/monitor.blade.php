@extends('layouts.master')

@section('content')
    <div ng-app="monitor">
        <div ng-controller="followersList" class="container-fluid">

            <div class="row">
                <div ng-repeat="follower in page" class="col-xs-4 col-sm-3 col-md-2 col-lg-1">
                    <img class="img-responsive img-rounded" src="@{{fixImageSize(follower.profile_image_url);}}" title="@{{follower.screen_name}}" alt="@{{follower.screen_name}}" />
                    <a href="http://twitter.com/@{{follower.screen_name}}" target="_blank">@{{follower.screen_name}}</a>
                </div>
            </div>

            <div ng-click="prevPage();" class="pager-button enabled-@{{hasPrevPage}}">Previous</div>
            <div>Page: @{{currentPage}}</div>
            <div ng-click="nextPage();" class="pager-button enabled-@{{hasNextPage}}">Next</div>

        </div>
    </div>
@stop
