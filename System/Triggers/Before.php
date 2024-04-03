<?php

namespace TeleBot\System\Triggers;

use Attribute;
use TeleBot\System\Messages\Inbound;

#[Attribute(Attribute::TARGET_CLASS)]
class Before
{

    /**
     * default constructor
     */
    public function __construct(public ?string $eventType = null) {}

    /**
     * validate event
     *
     * @return bool
     */
    public function apply(): bool
    {
        $valid = $this->eventType && $this->eventType !== Inbound::event()['event_type'];
        return !$this->eventType ?? $valid;
    }

}