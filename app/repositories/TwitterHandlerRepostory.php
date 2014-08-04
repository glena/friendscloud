<?php

class TwitterHandlerRepostory {

    public function getByUUID($uuid)
    {
        return TwitterHandler::where('uuid', '=', $uuid)->first();
    }

} 