<?php

namespace TeleBot\System\Validators;

use TeleBot\System\Interfaces\IValidator;

class RegexValidator implements IValidator
{

    /**
     * default constructor
     *
     * @param string $regex
     */
    public function __construct(public string $regex) {}

    /**
     * @inheritDoc
     */
    public function isValid(mixed $data): bool
    {
        return (bool) preg_match("/{$this->regex}/i", $data);
    }
}