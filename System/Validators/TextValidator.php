<?php

namespace TeleBot\System\Validators;

use TeleBot\System\Interfaces\IValidator;

class TextValidator implements IValidator
{

    /**
     * default constructor
     *
     * @param int|null $minLength
     * @param string|null $equals
     * @param string|null $regex
     */
    public function __construct(
        public ?int    $minLength = null,
        public ?string $equals = null,
        public ?string $regex = null
    ) {}

    /**
     * @inheritDoc
     */
    public function isValid(mixed $data): bool
    {
        if (
            ($this->minLength && $this->minLength > strlen($data))
            || ($this->equals && $this->equals !== $data)
            || ($this->regex && !((bool)preg_match("/{$this->regex}/i", $data)))
        ) return false;
        return true;
    }
}