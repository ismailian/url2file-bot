<?php

namespace TeleBot\System;

use TeleBot\System\Messages\Inbound;

class SessionManager
{

    /** @var string|mixed $sessionId */
    protected static string $sessionId;

    /** @var array in-memory session */
    protected static array $cached;

    /**
     * get session prop value
     *
     * @param string $path
     * @return mixed
     */
    protected static function getProp(string $path): mixed
    {
        $tmp = self::$cached;
        $keys = explode('.', $path);
        $lastKey = $keys[count($keys) - 1];

        foreach ($keys as $key) {
            if (isset($tmp[$key])) {
                if ($key == $lastKey) return $tmp[$key];
                $tmp = $tmp[$key];
            }
        }

        return null;
    }

    /**
     * initialize session
     *
     * @return SessionManager
     */
    public static function start(): SessionManager
    {
        if (empty(self::$sessionId)) {
            try {
                $event = Inbound::event()['data'];
                foreach (array_keys($event) as $key) {
                    if ($key !== 'update_id') {
                        self::$sessionId = $event[$key]['from']['id'];
                        break;
                    }
                }

                if (!file_exists('session') && !is_dir('session')) {
                    mkdir('session');
                }
            } catch (\Exception) {}
        }

        $sessionKey = 'session/' . self::$sessionId . '.json';
        $session = (new self);
        if (file_exists($sessionKey)) {
            $session::$cached = json_decode(file_get_contents($sessionKey), true);
            return $session;
        }

        $session::$cached = ['state' => 'started'];
        file_put_contents($sessionKey, json_encode($session::$cached, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
        return $session;
    }

    /**
     * get session key value
     *
     * @param string|null $key
     * @return mixed
     */
    public static function get(string $key = null): mixed
    {
        if (empty(self::$cached)) self::start();

        return $key ? self::getProp($key) : self::$cached;
    }

    /**
     * set session data
     *
     * @param array $data
     * @param string $state
     * @return void
     */
    public static function set(array $data, string $state = 'started'): void
    {
        if (empty(self::$sessionId) || empty(self::$cached)) {
            self::start();
        }

        $sessionKey = 'session/' . self::$sessionId . '.json';
        self::$cached = ['state' => $state, ...$data];
        file_put_contents($sessionKey, json_encode(self::$cached, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
    }

}
