<?php

class TwitterController extends BaseController {

    protected $server;
    protected $twitter;

    public function __construct(TwitterDecorator $twitter)
    {
        $this->twitter = $twitter;

        $this->beforeFilter(function(){
            $this->initServer();
        }, ['only' => ['login', 'callback']]);
    }

    protected function initServer()
    {
        $this->server = new League\OAuth1\Client\Server\Twitter(array(
            'identifier' => Config::get('thujohn/twitter::CONSUMER_KEY'),
            'secret' => Config::get('thujohn/twitter::CONSUMER_SECRET'),
            'callback_uri' => Config::get('thujohn/twitter::CALLBACK'),
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

        $this->initializeIndexing();

        return Redirect::route('monitor');
    }

    protected function initializeIndexing()
    {
        $userinfo = $this->twitter->getCredentials();
    }

    public function friends()
    {
        return $this->genericUsersResponse(function($params) {
            return $this->twitter->getFriends($params);
        });
    }

    public function followers()
    {
        return $this->genericUsersResponse(function($params) {
            return $this->twitter->getFollowers($params);
        });
    }

    protected function genericUsersResponse($closure)
    {
        $params = ['count' => 200];
        $response = [];
        $cursor = null;

        do
        {
            if ($cursor)
            {
                $params['cursor'] = $cursor;
            }

            $data = $closure($params);
            $processed_data = $this->mapUsersResponse($data);

            $response = array_merge($response, $processed_data);
        }
        while($cursor = $data->next_cursor_str);


        return $response;
    }

    protected function mapUsersResponse($data)
    {
        return array_map(function($friend){
            return $this->twitter->mapUser($friend);
        },$data->users);
    }

    public function userData()
    {
        return $this->twitter->mapUser($this->twitter->getCredentials());
    }
}
