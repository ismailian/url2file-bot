<?php

namespace TeleBot\System;

class BaseHttp
{

    /**
     * add http header
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public static function addHeader(string $key, string $value): void
    {
        header("$key: $value");
    }

    /**
     * set http status code
     *
     * @param int $code
     * @return void
     */
    public static function setStatusCode(int $code = 200): void
    {
        http_response_code($code);
    }

}