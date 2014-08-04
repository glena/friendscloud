<?php

class TwitterHandler extends \Eloquent {
	protected $fillable = [];

    public function following()
    {
        return $this->hasMany('TwitterHandler', 'twitter_handler_follow', 'twitter_handler_id_from', 'twitter_handler_id_to');
    }

    public function followers()
    {
        return $this->belongsToMany('TwitterHandler', 'twitter_handler_follow', 'twitter_handler_id_to', 'twitter_handler_id_from');
    }
}