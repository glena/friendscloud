<?php

class TwitterController extends BaseController {

    protected $server;

    protected function __construct()
    {
        $this->server = new League\OAuth1\Client\Server\Twitter(array(
            'identifier' => Config::get('app.twitter.key'),
            'secret' => Config::get('app.twitter.secret'),
            'callback_uri' => Config::get('app.twitter.callback'),
        ));
    }

    public function login()
    {
        // Retrieve temporary credentials
        $temporaryCredentials = $this->server->getTemporaryCredentials();

        // Store credentials in the session, we'll need them later
        Session::put('temporary_credentials', serialize($temporaryCredentials));
        Session::save();

        // Second part of OAuth 1.0 authentication is to redirect the
        // resource owner to the login screen on the server.
        $this->server->authorize($temporaryCredentials);
    }

    public function callback()
    {
        if (!Input::has('oauth_token') || !Input::has('oauth_verifier') || !Session::has('temporary_credentials'))
        {
            return Redirect::route('twitter-login');
        }

        $temporaryCredentials = unserialize(Session::pull('temporary_credentials'));

        // We will now retrieve token credentials from the server
        $tokenCredentials = $this->server->getTokenCredentials($temporaryCredentials, $_GET['oauth_token'], $_GET['oauth_verifier']);

        Session::put('twitterCredentials', serialize($tokenCredentials));

        return Redirect::route('friends-cloud');
    }

    public function following()
    {
        //followers/list
        $tokenCredentials = unserialize(Session::get('twitterCredentials'));
        $this->server->getUserScreenName($tokenCredentials);
    }

    public function followers()
    {
        //friends/list
        $tokenCredentials = unserialize(Session::get('twitterCredentials'));
        $this->server->getUserScreenName($tokenCredentials);
    }
}
