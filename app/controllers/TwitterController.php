<?php

class TwitterController extends BaseController {

    protected $server;

    protected function setServer()
    {
        if ($this->server) return;
        
        $this->server = new League\OAuth1\Client\Server\Twitter(array(
            'identifier' => Config::get('app.twitter.key'),
            'secret' => Config::get('app.twitter.secret'),
            'callback_uri' => Config::get('app.twitter.callback'),
        ));
    }

    public function login()
    {
        $this->setServer();

        // Retrieve temporary credentials
        $temporaryCredentials = $this->server->getTemporaryCredentials();

        // Store credentials in the session, we'll need them later
        Session::put('temporary_credentials', serialize($temporaryCredentials));

        // Second part of OAuth 1.0 authentication is to redirect the
        // resource owner to the login screen on the server.
        $this->server->authorize($temporaryCredentials);
    }

    public function callback()
    {
        $this->login();

        if (!Input::has('oauth_token') || !Input::has('oauth_verifier'))
        {
            Redirect::to('twitter/login');
        }

        $temporaryCredentials = unserialize(Session::get('temporary_credentials'));

        // We will now retrieve token credentials from the server
        $tokenCredentials = $this->server->getTokenCredentials($temporaryCredentials, $_GET['oauth_token'], $_GET['oauth_verifier']);

        dd($tokenCredentials);
    }
}
