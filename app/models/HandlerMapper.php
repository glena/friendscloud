<?php

class HandlerMapper {

    protected $repository;

    public function __construct(TwitterDecorator $twitter, TwitterHandlerRepostory $repository)
    {
        $this->twitter = $twitter;
        $this->repository = $repository;
    }

    public function map($userinfo, $oauth_token = null, $oauth_token_secret = null)
    {
        $handler = $this->repository->getByUUID($userinfo->id);

        if (!$handler)
        {
            $handler = new TwitterHandler();
        }

        $handler->uuid = $userinfo->id;
        $handler->handler = $userinfo->screen_name;
        $handler->name = $userinfo->name;
        $handler->location = $userinfo->location;
        $handler->description = $userinfo->description;
        $handler->status = 'pending';
        $handler->oauth_token = $oauth_token;
        $handler->oauth_token_secret = $oauth_token_secret;
        $handler->image = str_replace('_normal.jpeg','_bigger.jpeg',$userinfo->profile_image_url);

        if (isset($userinfo->entities->url) &&
            isset($userinfo->entities->url->urls[0]) &&
            isset($userinfo->entities->url->urls[0]->expanded_url))
        {
            $handler->url = $userinfo->entities->url->urls[0]->expanded_url;
        }
        else
        {
            $handler->url = '';
        }

        return $handler;
    }
} 