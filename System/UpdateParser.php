<?php

namespace TeleBot\System;

use TeleBot\System\Exceptions\InvalidUpdate;
use TeleBot\System\Exceptions\InvalidMessage;

class UpdateParser
{

    /** @var array|string[] list of top level updates */
    static array $updates = [
        'message', 'edited_message', 'callback_query',
        'inline_query', 'chosen_inline_result',
        'shipping_query', 'pre_checkout_query',
        'channel_post', 'edited_channel_post',
        'poll', 'poll_answer',
        'my_chat_member', 'chat_member', 'chat_join_request'
    ];

    /** @var array list of message types */
    static array $messageTypes = [
        'text', 'photo', 'video', 'audio', 'voice',
        'animation', 'document', 'contact', 'location',
        'poll', 'dice', 'sticker', 'game'
    ];

    /**
     * parse telegram update
     *
     * @param array $data
     * @return array
     * @throws InvalidUpdate|InvalidMessage
     */
    public static function parseUpdate(array $data): array
    {
        $event = ['data' => $data];

        /** get update id */
        $event['event_id'] = $data['update_id'] ?? null;
        unset($data['update_id']);

        /** get top level update type */
        $keys = array_keys($data);
        $updateName = $keys[0];

        if (empty($updateName) || !in_array($updateName, self::$updates))
            throw new InvalidUpdate('Unrecognized update type: ' . $updateName);

        $event['type'] = str_replace('_', '', ucwords($updateName, '_')) . '::class';

        /** check for message types */
        if (in_array($updateName, ['message', 'edited_message'])) {
            $data[$updateName] = self::parseMessage($data[$updateName]);
            $event['type'] = $data[$updateName]['type'];
        }

        return $event;
    }

    /**
     * parse message
     *
     * @param array $data
     * @return array
     * @throws InvalidMessage
     */
    public static function parseMessage(array $data): array
    {
        $message = ['data' => $data];

        /** get static properties */
        $message['id'] = $data['message_id'] ?? null;
        $message['date'] = $data['date'] ?? null;
        $message['from'] = $data['from'] ?? null;
        $message['chat'] = $data['chat'] ?? null;
        $message['caption'] = $data['caption'] ?? null;

        $unset = ['message_id', 'date', 'from', 'chat', 'caption', 'edit_date'];
        array_map(function($k) use (&$data) { unset($data[$k]); }, $unset);

        /** determine message type */
        $keys = array_keys($data);
        $messageType = $keys[0];

        if (empty($messageType) || !in_array($messageType, self::$messageTypes)) {
            throw new InvalidMessage('Unrecognized message type: ' . $messageType);
        }

        $message['type'] = str_replace('_', '', ucwords($messageType, '_')) . '::class';
        return $message;
    }

}