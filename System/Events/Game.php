<?php

namespace TeleBot\System\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;

#[Attribute(Attribute::TARGET_METHOD)]
class Game implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        $key = isset($event['data']['edited_message']) ? 'edited_message' : 'message';
        return isset($event['data'][$key]) && isset($event['data'][$key]['game']);
    }
}