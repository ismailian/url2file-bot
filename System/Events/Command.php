<?php

namespace TeleBot\System\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;

#[Attribute(Attribute::TARGET_METHOD)]
class Command implements IEvent
{

    /**
     * default constructor
     *
     * @param ?string $command
     */
    public function __construct(public ?string $command = null) {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        $key = isset($event['data']['edited_message']) ? 'edited_message' : 'message';
        if (!empty($this->command))
            $this->command = str_starts_with($this->command, '/') ? '' : "/{$this->command}";

        foreach ($event['data'][$key]['entities'] ?? [] as $entity) {
            if ($entity['type'] == 'bot_command') {
                if (!$this->command || substr($event['data'][$key]['text'], $entity['offset'], $entity['length']) == $this->command) {
                    return true;
                }
            }
        }

        return false;
    }
}