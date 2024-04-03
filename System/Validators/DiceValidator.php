<?php

namespace TeleBot\System\Validators;

use TeleBot\System\Interfaces\IValidator;

class DiceValidator implements IValidator
{

    /**
     * default constructor
     *
     * @param int|null $min
     * @param int|null $lessThan
     * @param int|null $greaterThan
     * @param int|null $equals
     */
    public function __construct(
        public ?int $min = null,
        public ?int $lessThan = null,
        public ?int $greaterThan = null,
        public ?int $equals = null,
    ) {}

    /**
     * @inheritDoc
     */
    public function isValid(mixed $data): bool
    {
        if (
            !is_numeric($data['value'])
            || ($this->min && $this->min > $data['value'])
            || ($this->equals && $this->equals !== $data['value'])
            || ($this->lessThan && $this->lessThan <= $data['value'])
            || ($this->greaterThan && $this->greaterThan >= $data['value'])
        ) return false;
        return true;
    }
}