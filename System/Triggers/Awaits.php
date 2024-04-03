<?php

namespace TeleBot\System\Triggers;

use Attribute;
use TeleBot\System\SessionManager;
use TeleBot\System\Interfaces\IEvent;

#[Attribute(Attribute::TARGET_METHOD)]
class Awaits implements IEvent
{

    /**
     * default constructor
     *
     * @param string $key
     * @param string $value
     */
    public function __construct(public string $key, public string $value) {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        return SessionManager::get($this->key) == $this->value;
    }
}