<?php

namespace TeleBot\System\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Types\IncomingContact;

#[Attribute(Attribute::TARGET_METHOD)]
class Contact implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingContact|bool
    {
        $key = isset($event['data']['edited_message']) ? 'edited_message' : 'message';
        $isContact = isset($event['data'][$key]) && isset($event['data'][$key]['contact']);
        if (!$isContact) return false;

        return new IncomingContact($event['data'][$key]['contact']);
    }
}