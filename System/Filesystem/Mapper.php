<?php

namespace TeleBot\System\Filesystem;

class Mapper
{

    /**
     * inject handler
     *
     * @param object $handler
     * @param string $method
     * @param ...$args
     * @return void
     */
    public static function call(object $handler, string $method, ...$args): void
    {
        call_user_func([$handler, $method], ...$args);
    }

}