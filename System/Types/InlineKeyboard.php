<?php

namespace TeleBot\System\Types;

class InlineKeyboard
{

    const URL = 'url';
    const PAY = 'pay';
    const WEB_APP = 'web_app';
    const LOGIN_URL = 'login_url';
    const CALLBACK_GAME = 'callback_game';
    const CALLBACK_DATA = 'callback_data';
    const SWITCH_INLINE_QUERY = 'switch_inline_query';
    const SWITCH_INLINE_QUERY_CURRENT_CHAT = 'switch_inline_query_current_chat';
    const SWITCH_INLINE_QUERY_CHOSEN_CHAT = 'switch_inline_query_chosen_chat';

    /** @var array collection of buttons */
    protected array $buttons = [];

    /** @var int $max max number of buttons in a row */
    protected int $max = 3;

    public function setRowMax(int $max = 3): InlineKeyboard
    {
        $this->max = $max;
        return $this;
    }

    /**
     * add keyboard button
     *
     * @param string $text
     * @param mixed $value
     * @param string $type
     * @return $this
     */
    public function addButton(string $text, mixed $value, string $type = InlineKeyboard::URL): InlineKeyboard
    {
        $this->buttons[] = ['text' => $text, $type => is_array($value) ? json_encode($value, JSON_UNESCAPED_SLASHES) : $value];
        return $this;
    }

    /**
     * get a string representation of this InlineKeyboard
     *
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_SLASHES);
    }

    /**
     * get an array representation of this InlineKeyboard
     *
     * @return array
     */
    public function toArray(): array
    {
        $rows = [];
        foreach ($this->buttons as $button) {
            $idx = count($rows) - 1;
            if (count($rows) > 0 && count($rows[$idx]) < $this->max) {
                $rows[$idx][] = $button;
                continue;
            }

            $rows[] = [$button];
        }

        return $rows;
    }

}