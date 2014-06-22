<?php

class TwitterDecorator {

    protected $logged;
    protected $token;


    public function __construct()
    {
        $this->logged = Session::has('access_token');

        if ($this->logged)
        {
            $access_token = Session::get('access_token');
            $this->token = $access_token['oauth_token'];
        }

    }

    protected function buildKey($name, $arguments)
    {
        $key = $this->token . $name . serialize($arguments);
        return sha1($key);
    }

    public function __call($name, $arguments)
    {
        if (!$this->logged) return null;

        $key = $this->buildKey($name, $arguments);

        if (!Cache::has($key))
        {
            $return = call_user_func("Twitter::$name", $arguments);
            Cache::forever($key, $return);
        }
        else
        {
            $return = Cache::get($key);
        }



        return $return;
    }

} 