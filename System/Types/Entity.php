<?php

namespace TeleBot\System\Types;

class Entity
{

    /**
     * default constructor
     *
     * @param string $text
     * @param array $entity
     */
    public function __construct(protected string $text, protected array $entity) {}

}