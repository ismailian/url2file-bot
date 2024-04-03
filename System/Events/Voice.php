<?php

namespace TeleBot\System\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Types\IncomingVoice;

#[Attribute(Attribute::TARGET_METHOD)]
class Voice implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingVoice|bool
    {
        $key = isset($event['data']['edited_message']) ? 'edited_message' : 'message';
        $isVoice = isset($event['data'][$key]) && isset($event['data'][$key]['voice']);
        if (!$isVoice) return false;

        return new IncomingVoice($event['data'][$key]['voice']);
    }
}