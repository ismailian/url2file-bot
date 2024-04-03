<?php

namespace TeleBot\System\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Types\IncomingDice;
use TeleBot\System\Interfaces\IValidator;

#[Attribute(Attribute::TARGET_METHOD)]
class Dice implements IEvent
{

    /**
     * default constructor
     *
     * @param IValidator|null $Validator
     */
    public function __construct(public ?IValidator $Validator = null) {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingDice|bool
    {
        $key = isset($event['data']['edited_message']) ? 'edited_message' : 'message';
        $isDice = isset($event['data'][$key]) && isset($event['data'][$key]['dice']);
        if (!$isDice || ($this->Validator && !$this->Validator->isValid($event['data'][$key]['dice'])))
            return false;

        return new IncomingDice($event['data'][$key]['dice']);
    }
}