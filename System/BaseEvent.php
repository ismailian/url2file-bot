<?php

namespace TeleBot\System;

use GuzzleHttp\Client;
use TeleBot\System\Messages\Inbound;
use TeleBot\System\Exceptions\InvalidUpdate;
use TeleBot\System\Exceptions\InvalidMessage;

class BaseEvent
{

    /** @var array $event */
    protected array $event;

    /** @var array $config */
    public array $config;

    protected object $telegram;

    protected Client $client;

    /**
     * default constructor
     *
     * @throws InvalidUpdate|InvalidMessage
     */
    public function __construct()
    {
        $userId = null;
        $this->event = Inbound::event()['data'];
        foreach (array_keys($this->event) as $key) {
            if ($key !== 'update_id') {
                $userId = $this->event[$key]['from']['id'];
                break;
            }
        }

        $this->telegram = (new BotClient())
            ->setToken(getenv('TG_BOT_TOKEN'))
            ->setChatId($userId);
    }

}