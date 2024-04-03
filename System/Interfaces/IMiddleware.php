<?php

namespace TeleBot\System\Interfaces;

interface IMiddleware
{

    /**
     * method to handle incoming events
     *
     * @return void
     */
    public function handle(): void;

}