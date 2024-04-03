<?php

namespace TeleBot\System\Types;

class IncomingDice
{
    
    /** @var array $dice */
    protected array $dice;
    
    public function __construct(array $dice)
    {
        $this->dice = $dice;
    }

    /**
     * get dice emoji
     * @return string
     */
    public function getEmoji(): string
    {
        return $this->dice['emoji'];
    }

    /**
     * get dice value
     *
     * @return int
     */
    public function getValue(): int
    {
        return $this->dice['value'];
    }
    
}