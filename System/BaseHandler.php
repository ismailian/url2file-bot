<?php

namespace TeleBot\System;

use ReflectionClass;
use ReflectionMethod;
use ReflectionException;
use TeleBot\System\Triggers\Awaits;
use TeleBot\System\Messages\Inbound;
use TeleBot\System\Filesystem\Handler;
use TeleBot\System\Filesystem\Collector;
use TeleBot\System\Filesystem\Bootstrap;
use TeleBot\System\Exceptions\InvalidUpdate;
use TeleBot\System\Exceptions\InvalidMessage;

class BaseHandler
{

    /** @var array $config */
    protected array $config = [];

    /** @var array $event */
    protected array $event;

    /** @var object $telegram */
    protected object $telegram;

    /** @var Handler $handler */
    protected Handler $handler;

    /**
     * initialize handler
     *
     * @throws ReflectionException
     * @throws InvalidUpdate|InvalidMessage
     */
    public function init(): bool
    {
        (new Bootstrap())->setup();
        $this->event = Inbound::event();
        $this->handler = new Handler();

        $handlers = Collector::getNamespacedFiles('App/Handlers');
        foreach ($handlers as $handler) {
            $refClass = new ReflectionClass($handler);
            foreach ($refClass->getMethods() as $method) {
                if ($this->runAwaits($method)) {
                    foreach ($method->getAttributes() as $attr) {
                        if (!str_ends_with($attr->getName(), 'Awaits')) {
                            if (($result = $attr->newInstance()?->apply($this->event))) {
                                $this->handler->setConfig($this->config)->assign(
                                    $refClass->newInstance($attr),
                                    $method->name, $result
                                );
                                return true;
                            }
                        }
                    }
                }
            }
        }

        return false;
    }

    /**
     * evaluate awaits
     *
     * @param ReflectionMethod $method
     * @return bool
     */
    private function runAwaits(ReflectionMethod $method): bool
    {
        $awaits = $method->getAttributes(Awaits::class);
        foreach ($awaits as $await) {
            if (!($await->newInstance()->apply([]))) {
                return false;
            }
        }

        return true;
    }

}