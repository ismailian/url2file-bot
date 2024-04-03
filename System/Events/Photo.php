<?php

namespace TeleBot\System\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Types\IncomingPhoto;
use TeleBot\System\Interfaces\IValidator;

#[Attribute(Attribute::TARGET_METHOD)]
class Photo implements IEvent
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
    public function apply(array $event): IncomingPhoto|bool
    {
        $key = isset($event['data']['edited_message']) ? 'edited_message' : 'message';
        $isPhoto = isset($event['data'][$key]) && isset($event['data'][$key]['photo']);
        if (!$isPhoto) return false;

        if ($this->Validator && !$this->Validator->isValid($event['data'][$key]['photo'])) return false;
        return new IncomingPhoto($event['data'][$key]['photo']);
    }
}