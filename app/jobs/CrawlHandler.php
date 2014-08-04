<?php

class CrawlHandler {

    protected $mapper;
    protected $twitterhandlerRepository;

    public function __construct(HandlerMapper $mapper, TwitterHandlerRepostory $twitterhandlerRepository)
    {
        $this->mapper = $mapper;
        $this->twitterhandlerRepository = $twitterhandlerRepository;
    }

    public function main($job, $data, $mapRelations = true)
    {
        //$job->delete();return;

        $handler = $this->twitterhandlerRepository->find($data['handler_id']);

        $twitter = new \Thujohn\Twitter\Twitter([
            'token' => $handler->oauth_token,
            'secret' => $handler->oauth_token_secret
        ]);

        $params = ['count' => 200];
        $cursor = null;

        echo "Processing: @{$handler->handler}\n";

        do
        {
            if ($cursor)
            {
                $params['cursor'] = $cursor;
            }

            $data = $twitter->getFriends($params);

            foreach ($data->users as $item)
            {
                $relatedHandler = $this->mapper->map($item);

                echo "\t Saving: @{$relatedHandler->handler}\n";

                $this->twitterhandlerRepository->persist($relatedHandler);

                $handler->following()->save($relatedHandler);

                if ($mapRelations)
                {
                    Queue::push('CrawlHandler@secondLevel', array('handler' => $relatedHandler));
                }
            }
        }
        while($cursor = $data->next_cursor_str);

        $handler->status = 'first-level';
        $this->twitterhandlerRepository->persist($handler);

        $job->delete();
    }

    public function secondLevel($job, $data)
    {
        $this->main($job, $data, false);
    }

} 