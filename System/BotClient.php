<?php

namespace TeleBot\System;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\Exception\GuzzleException;
use TeleBot\System\Types\IncomingDice;

class BotClient
{

    /** @var string $baseUrl */
    protected string $baseUrl = 'https://api.telegram.org/bot{token}/';

    /** @var string $token */
    protected string $token = '';

    /** @var string $chatId */
    protected string $chatId = '';

    /** @var string $mode */
    protected string $mode = 'html';

    /** @var ?Client $api */
    protected ?Client $api = null;

    /** @var array $options options to send with the message */
    protected array $options = [];

    /** @var string $lastMessageId */
    protected string $lastMessageId;

    /** @var ?string $lastUploadId */
    protected ?string $lastUploadId;

    /** @var array $endpoints */
    protected array $endpoints = [
        'updates' => 'getUpdates',
        'message' => 'sendMessage',
        'photo' => 'sendPhoto',
        'video' => 'sendVideo',
        'document' => 'sendDocument',
        'delete' => 'deleteMessage',
        'action' => 'sendChatAction',
        'user' => 'getChatMember',
        'edit' => 'editMessageText',
        'dice' => 'sendDice',
    ];

    /**
     * default constructor
     */
    function __construct()
    {
        $this->api = new Client();
    }

    /**
     * set bot token
     *
     * @param string $token bot token to be used in requests
     * @return BotClient
     */
    public function setToken(string $token): BotClient
    {
        $this->token = $token;
        return $this;
    }

    /**
     * set message parse mode
     *
     * @param string $mode mode to use with message
     * @return BotClient
     */
    public function setParseMode(string $mode = 'text'): BotClient
    {
        $this->mode = $mode;
        return $this;
    }

    /**
     * set recipient chat id
     *
     * @param string $chatId recipient chat id
     * @return BotClient
     */
    public function setChatId(string $chatId): BotClient
    {
        $this->chatId = $chatId;
        return $this;
    }

    /**
     * extra options to send with the message
     *
     * @param array $options
     * @return BotClient
     */
    public function withOptions(array $options): BotClient
    {
        $this->options = $options;
        return $this;
    }

    /**
     * send request to API
     *
     * @param string $action
     * @param array $data
     * @return array|null
     * @throws Exception
     */
    protected function post(string $action, array $data): ?array
    {
        try {
            $endpoint = $this->baseUrl . $this->endpoints[$action];
            $endpoint = str_replace('{token}', $this->token, $endpoint);

            /** use [multipart] when uploading */
            if (in_array($action, ['photo', 'video', 'audio', 'document', 'voice'])) {
                $ak = fn($arr) => array_keys($arr);
                $av = fn($arr) => array_values($arr);
                $response = $this->api->request('POST', $endpoint, [
                    'multipart' => [
                        ['name' => 'chat_id', 'contents' => $this->chatId],
                        ...array_map(fn($k, $v) => ['name' => $k, 'contents' => $v], $ak($this->options), $av($this->options)),
                        ...array_map(fn($k, $v) => ['name' => $k, 'contents' => $v], $ak($data), $av($data)),
                    ],
                ]);
            } else {
                $response = $this->api->request('POST', $endpoint, [
                    'json' => ['chat_id' => $this->chatId, ...$data, ...$this->options]
                ]);
            }

            if ($response->getStatusCode() !== 200) return null;
            $body = json_decode($response->getBody(), true);

            /* store last message id */
            if (in_array($action, ['message', 'photo', 'video', 'audio', 'voice', 'document', 'contact'])) {
                $this->lastMessageId = $body['result']['message_id'];

                /** store last upload id */
                if (in_array($action, ['photo', 'video', 'audio', 'voice', 'document'])) {
                    $this->lastUploadId = $body['result']['video']['file_id'] ?? null;
                }
            }

            return $body['ok'] ? $body : null;
        } catch (GuzzleException $e) {
            die($e->getMessage());
        }
        return null;
    }

    /**
     * send request to API
     *
     * @param string $action
     * @param array $query
     * @return array|null
     */
    protected function get(string $action, array $query): ?array
    {
        try {
            $endpoint = $this->baseUrl . $this->endpoints[$action];
            $endpoint = str_replace('{token}', $this->token, $endpoint);
            $response = $this->api->request('GET', $endpoint, [
                'query' => ['chat_id' => $this->chatId, ...$query]
            ]);

            if ($response->getStatusCode() !== 200) return null;
            $body = json_decode($response->getBody(), true);

            return $body['ok'] ? $body : null;
        } catch (GuzzleException $e) {}
        return null;
    }

    /**
     * send an action
     *
     * @param string $action action to send
     * @return BotClient
     * @throws Exception
     */
    public function sendAction(string $action): BotClient
    {
        $this->post('action', [
            'chat_id' => $this->chatId,
            'action' => $action
        ]);

        return $this;
    }

    /**
     * send a text message
     *
     * @param string $text text message to send
     * @param bool $withAction send action
     * @return bool returns true on success, otherwise false
     * @throws Exception
     */
    public function sendMessage(string $text, bool $withAction = false): bool
    {
        if ($withAction) $this->sendAction('typing');
        $data = $this->post('message', [
            'text' => $text,
            'parse_mode' => $this->mode,
        ]);

        return $data && $data['ok'] == true;
    }

    /**
     * send a photo message with caption
     *
     * @param string $imagePath image path
     * @param string|null $caption caption to send with image
     * @param bool $withAction send action indicator
     * @return bool returns true on success, otherwise false
     * @throws Exception
     */
    public function sendPhoto(string $imagePath, string $caption = null, bool $withAction = false, bool $asUrl = false): bool
    {
        if ($withAction) $this->sendAction('upload_photo');
        $data = $this->post('photo', [
            'photo' => $asUrl ? $imagePath : Utils::tryFopen($imagePath, 'r'),
            'caption' => $caption ?? '',
            'parse_mode' => $this->mode
        ]);

        return $data && $data['ok'] == true;
    }

    /**
     * send a video message with caption
     *
     * @param string $videoPath the video url to send
     * @param string|null $caption caption to send with image
     * @param bool $withAction send action indicator
     * @param bool $asUrl weather the provider video is an ID or Url
     * @return bool returns true on success, otherwise false
     * @throws Exception
     */
    public function sendVideo(string $videoPath, string $caption = null, bool $withAction = false, bool $asUrl = false): bool
    {
        if ($withAction) $this->sendAction('upload_video');
        $data = $this->post('video', [
            'video' => $asUrl ? $videoPath : Utils::tryFopen($videoPath, 'r'),
            'caption' => $caption ?? '',
            'parse_mode' => $this->mode
        ]);

        return $data && $data['ok'] == true;
    }

    /**
     * send a document message with caption
     *
     * @param string $fileUrl
     * @param string|null $caption caption to send with image
     * @param bool $withAction send action indicator
     * @param bool $asUrl
     * @return bool returns true on success, otherwise false
     * @throws Exception
     */
    public function sendDocument(string $fileUrl, string $caption = null, bool $withAction = false, bool $asUrl = false): bool
    {
        if ($withAction) $this->sendAction('upload_document');
        $data = $this->post('document', [
            'document' => $asUrl ? $fileUrl : Utils::tryFopen($fileUrl, 'r'),
            'caption' => $caption ?? '',
            'parse_mode' => $this->mode
        ]);

        return $data && $data['ok'] == true;
    }

    /**
     * send a dice message
     *
     * @param string $emoji
     * @return IncomingDice|bool
     * @throws Exception
     */
    public function sendDice(string $emoji): IncomingDice|bool
    {
        $data = $this->post('dice', [
            'emoji' => $emoji,
        ]);

        return ($data && $data['ok']) ? new IncomingDice($data['result']['dice']) : false;
    }

    /**
     * delete a message
     *
     * @param string $messageId id of message to delete
     * @return bool
     * @throws Exception
     */
    public function deleteMessage(string $messageId): bool
    {
        $data = $this->post('delete', [
            'message_id' => $messageId
        ]);

        return $data && $data['ok'] == true;
    }

    /**
     * delete last message sent by bot
     *
     * @return BotClient
     * @throws Exception
     */
    public function deleteLastMessage(): BotClient
    {
        if (!empty($this->lastMessageId)) {
            $this->deleteMessage($this->lastMessageId);
            $this->lastMessageId = '';
        }

        return $this;
    }

    /**
     * get user info
     *
     * @param string $userId
     * @param bool $withPicture
     * @return array|null
     */
    public function getUser(string $userId, bool $withPicture = false): ?array
    {
        $data = $this->get('user', ['user_id' => $userId]);
        if (empty($data)) return null;

        return $data['result']['user'];
    }

    /**
     * get last sent message id
     *
     * @return int|null
     */
    public function getLastMessageId(): ?int
    {
        return $this->lastMessageId ?? null;
    }

    /**
     * get last sent video id
     *
     * @return string|null
     */
    public function getLastUploadId(): ?string
    {
        return $this->lastUploadId;
    }

    /**
     * edit message text
     *
     * @param string $messageId
     * @param string $text
     * @return bool
     * @throws Exception
     */
    public function editMessage(string $messageId, string $text): bool
    {
        $data = $this->post('edit', [
            'message_id' => $messageId,
            'text' => $text,
            'parse_mode' => $this->mode
        ]);

        return $data && $data['ok'] == true;
    }
}