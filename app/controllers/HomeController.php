<?php

class HomeController extends BaseController {

    protected $twitter;

    public function __construct(TwitterDecorator $twitter)
    {
        $this->twitter = $twitter;
    }

    public function index()
    {
        $userinfo = $this->twitter->getCredentials();

        return View::make('home.index', array('userinfo' => $userinfo));
    }

    public function cloud()
    {
        return View::make('home.cloud');
    }

}
