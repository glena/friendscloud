<?php

class TwitterHandlerRepostory {

    public function find($id) {
        return TwitterHandler::find($id);
    }

    public function getByUUID($uuid)
    {
        return TwitterHandler::where('uuid', '=', $uuid)->first();
    }

    public function persist(TwitterHandler $handler)
    {
        $handler->save();
    }

} 