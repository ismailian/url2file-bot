<?php

namespace TeleBot\System\Types;

class IncomingUrl extends Entity
{

    /**
     * get full message text
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * get parsed url
     *
     * @return string|null
     */
    public function getUrl(): ?string
    {
        if (empty($this->text)) return null;

        $encoding = mb_detect_encoding($this->text);
        if ($encoding !== 'ASCII') {
            $text = mb_convert_encoding($this->text, 'UTF-16', $encoding);
            $text = substr($text, $this->entity['offset'] * 2, $this->entity['length'] * 2);
            return mb_convert_encoding($text, 'UTF-8', 'UTF-16');
        }

        return substr($this->text,
            $this->entity['offset'],
            $this->entity['length']
        );
    }

    /**
     * stringified url object
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getUrl() ?? "";
    }

}