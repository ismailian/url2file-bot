<?php

namespace TeleBot\System\Interfaces;

interface IEvent
{

    /**
     * verify event type
     *
     * @param array $event
     * @return mixed
     */
    public function apply(array $event): mixed;

}