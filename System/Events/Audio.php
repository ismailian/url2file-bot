<?php

namespace TeleBot\System\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Types\IncomingAudio;

#[Attribute(Attribute::TARGET_METHOD)]
class Audio implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingAudio|bool
    {
        $key = isset($event['data']['edited_message']) ? 'edited_message' : 'message';
        $isAudio = isset($event['data'][$key]) && isset($event['data'][$key]['audio']);
        if (!$isAudio) return false;

        return new IncomingAudio($event['data'][$key]['audio']);
    }
}