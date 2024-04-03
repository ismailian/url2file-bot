<?php

namespace TeleBot\System;

use TeleBot\System\Interfaces\IMiddleware;

class BaseMiddleware implements IMiddleware
{
    /**
     * @inheritDoc
     */
    public function handle(): void {}
}