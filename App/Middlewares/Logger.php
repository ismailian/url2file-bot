<?php

namespace TeleBot\App\Middlewares;

use TeleBot\System\BaseMiddleware;
use TeleBot\System\Triggers\Before;

#[Before]
class Logger extends BaseMiddleware
{

    /**
     * @inheritDoc
     */
    public function handle(): void
    {
        // todo: process event here
    }

}