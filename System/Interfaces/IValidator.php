<?php

namespace TeleBot\System\Interfaces;

interface IValidator
{

    /**
     * check if data matches the validation criteria
     *
     * @param mixed $data
     * @return bool
     */
    public function isValid(mixed $data): bool;

}