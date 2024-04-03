<?php

namespace TeleBot\System;

use Exception;

class BotHandler extends BaseHandler
{

    /**
     * start event monitoring
     *
     * @return void
     */
    public function start(): void
    {
        try {
            if ($this->init()) {
                $this->handler->run();
            }
        } catch (Exception $ex) {}
    }

}