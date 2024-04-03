<?php

namespace TeleBot\System;

class ExceptionHandler
{

    /**
     * handle runtime errors
     *
     * @param int $code
     * @param string $message
     * @param string|null $file
     * @param int|null $line
     * @param array $context
     * @return void
     */
    public static function onError(int $code, string $message, string $file = null, int $line = null, array $context = []): void
    {
        self::log([
            'file' => $file,
            'line' => $line,
            'error' => $message,
            'trace' => $context
        ]);
    }

    /**
     * save log file
     *
     * @param array $data
     * @return void
     */
    protected static function log(array $data): void
    {
        /** create logs dir if it does not already exist */
        if (!file_exists('logs') && !is_dir('logs')) {
            mkdir('logs');
        }

        $logPath = 'logs/' . date('Y-m-d__H-i-s') . '.log';
        file_put_contents($logPath, json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

    /**
     * handle runtime exceptions
     *
     * @param $exception
     * @return void
     */
    public static function onException($exception): void
    {
        self::log([
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'error' => $exception->getMessage(),
            'trace' => $exception->getTrace()
        ]);
    }

}