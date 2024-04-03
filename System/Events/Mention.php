<?php

namespace TeleBot\System\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;

#[Attribute(Attribute::TARGET_METHOD)]
class Mention implements IEvent
{

    /**
     * default constructor
     *
     * @param string|null $username username to check for mentions
     */
    public function __construct(public ?string $username = null) {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        $key = isset($event['data']['edited_message']) ? 'edited_message' : 'message';
        $isMessage = isset($event['data'][$key]);
        $hasText = isset($event['data'][$key]['text']);
        $hasEntities = !empty($event['data'][$key]['entities']);

        if (!$isMessage || !$hasText || !$hasEntities) return false;
        foreach ($event['data'][$key]['entities'] as $entity) {
            if (isset($entity['type']) && $entity['type'] == 'mention') {
                return !$this->username || substr(
                    $event['data'][$key]['text'], $entity['offset'], $entity['length']
                ) == $this->username;
            }
        }

        return false;
    }
}