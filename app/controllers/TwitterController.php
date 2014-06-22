<?php

class TwitterController extends BaseController {

    protected $server;
    protected $twitter;

    public function __construct(TwitterDecorator $twitter)
    {
        $this->twitter = $twitter;

        $this->beforeFilter(function(){
            $this->initServer();
        });
    }

    protected function initServer()
    {
        $this->server = new League\OAuth1\Client\Server\Twitter(array(
            'identifier' => Config::get('twitter::CONSUMER_KEY'),
            'secret' => Config::get('twitter::CONSUMER_SECRET'),
            'callback_uri' => Config::get('twitter::callback'),
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

        Session::put('access_token', [
            'oauth_token' => $tokenCredentials->getIdentifier(),
            'oauth_token_secret' => $tokenCredentials->getSecret(),
        ]);

        return Redirect::route('friends-cloud');
    }

    public function friends()
    {
        $data = $this->twitter->getFriends(['count' => 200]);

        return [
            'loggeduser' => $this->userData(),
            'friends' => $this->mapUsersResponse($data),
        ];
    }

    public function userData()
    {
        return $this->twitter->mapUser($this->twitter->getCredentials());
    }

    public function followers()
    {
        $data = $this->twitter->getFollowers();
        return [
            'loggeduser' => $this->userData(),
            'friends' => $this->mapUsersResponse($data),
        ];
    }

    protected function mapUsersResponse($data)
    {
        $response = [];


        foreach ($data->users as $friend)
        {
            $response[] = $this->twitter->mapUser($friend);
        }

        return $response;
    }
}
