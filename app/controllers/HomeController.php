<?php

/*
http://laravel.com/docs/redis#configuration

Beanstalkd
Listen Address: 0.0.0.0
Listen Port: 13000
Max Job Size: 65535
Max Connections: 1024
Binlog Directory: /var/lib/beanstalkd/binlog


http://laravel.com/docs/redis
http://laravel.com/docs/queues

http://fideloper.com/ubuntu-beanstalkd-and-laravel4

*/

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

    public function monitor()
    {
        return View::make('home.monitor');
    }

}
