<?php

namespace TeleBot\System\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;

#[Attribute(Attribute::TARGET_METHOD)]
class EditedMessage implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        return isset($event['data']['edited_message']);
    }
}