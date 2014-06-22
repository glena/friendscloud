<?php

class HomeController extends BaseController {

    public function index()
    {
        return View::make('home.index');
    }

    public function cloud()
    {
        return View::make('home.cloud');
    }

}
