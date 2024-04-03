<?php

namespace TeleBot\System\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Interfaces\IValidator;
use TeleBot\System\Types\IncomingInlineQuery;

#[Attribute(Attribute::TARGET_METHOD)]
class InlineQuery implements IEvent
{

    /**
     * default constructor
     *
     * @param bool $allowEmpty capture empty query
     * @param IValidator|null $Validator query validator
     */
    public function __construct(public bool $allowEmpty = true, public ?IValidator $Validator = null) {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool|IncomingInlineQuery
    {
        if (!isset($event['data']['inline_query'])) return false;
        if (!$this->allowEmpty && empty($event['data']['inline_query']['query'])) return false;
        if ($this->Validator && !$this->Validator->isValid($event['data']['inline_query']['query'])) return false;

        return new IncomingInlineQuery(
            $event['data']['inline_query']['id'],
            $event['data']['inline_query']['query'],
            $event['data']['inline_query']['offset']
        );
    }
}