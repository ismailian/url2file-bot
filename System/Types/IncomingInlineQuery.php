<?php

namespace TeleBot\System\Types;

class IncomingInlineQuery
{

    /**
     * default constructor
     *
     * @param string $id
     * @param string|null $query
     * @param string|null $offset
     */
    public function __construct(public string $id, public ?string $query = "", public ?string $offset = "") {}

}