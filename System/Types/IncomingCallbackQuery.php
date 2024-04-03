<?php

namespace TeleBot\System\Types;

class IncomingCallbackQuery
{

    /** @var array $callbackQuery */
    protected mixed $callbackQuery;

    /**
     * default constructor
     *
     * @param int $messageId
     * @param mixed $callbackQuery
     */
    public function __construct(public int $messageId, mixed $callbackQuery)
    {
        $this->callbackQuery = $callbackQuery;
        if (!is_array($callbackQuery) && !empty($json = json_decode($callbackQuery, true))) {
            $this->callbackQuery = $json;
        }
    }

    /**
     * get query data
     *
     * @param string|null $key
     * @return string|array
     */
    public function __invoke(string $key = null): string|array
    {
        if (!is_array($this->callbackQuery))
            return $this->callbackQuery;

        if ($key && isset($this->callbackQuery[$key])) {
            return $this->callbackQuery[$key];
        }

        return $this->callbackQuery;
    }

}