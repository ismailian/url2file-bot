<?php

namespace TeleBot\System\Filesystem;

use TeleBot\System\Messages\Inbound;
use TeleBot\System\Messages\Outbound;
use TeleBot\System\UpdateParser;

class Bootstrap
{

    /** @var array $config */
    public static array $config;
    /** @var string $envFilename */
    protected string $envFilename = '.env';

    /**
     * setup necessary configurations to run the app
     *
     * @return void
     */
    public function setup(): void
    {
        $this->__env();
        self::$config = require_once 'config.php';

        $allowedId = $this->verifyIP();
        $validSignature = $this->verifySignature();
        $allowedRoute = $this->verifyRoute();
        $validPayload = $this->verifyPayload();
        if (!$allowedId || !$validSignature || !$allowedRoute || !$validPayload || !$this->verifyUserId())
            die();

        if (!empty(($async = getenv('ASYNC')))) {
            if ($async == 'true') {
                Outbound::close();
            }
        }
    }

    /**
     * load env configurations
     *
     * @return void
     */
    protected function __env(): void
    {
        if (file_exists($this->envFilename) && is_file($this->envFilename)) {
            $envFile = new \SplFileObject($this->envFilename);
            while (!$envFile->eof()) {
                $validLine = preg_match('/^(?<key>[!a-zA-Z]\S+)=(?<value>.+)?$/', ($line = trim($envFile->fgets())));
                if ($validLine) putenv(str_replace('"', '', $line));
            }
        }
    }

    /**
     * verify source IP address
     *
     * @return bool
     */
    private function verifyIP(): bool
    {
        if (!empty(($sourceIp = self::$config['ip']))) {
            return hash_equals($sourceIp, Inbound::ip());
        }

        return true;
    }

    /**
     * verify request signature
     *
     * @return bool
     */
    private function verifySignature(): bool
    {
        if (!empty(($signature = self::$config['signature']))) {
            return hash_equals($signature, Inbound::headers('X-Telegram-Bot-Api-Secret-Token'));
        }

        return true;
    }

    /**
     * verify request route
     *
     * @return bool
     */
    private function verifyRoute(): bool
    {
        if (!empty(($routes = self::$config['routes']))) {
            return in_array(Inbound::uri(), $routes);
        }

        return true;
    }

    /**
     * verify payload
     *
     * @return bool
     */
    private function verifyPayload(): bool
    {
        $payload = Inbound::context();
        if (isset($payload['update_id'])) {
            if (!empty(array_intersect(UpdateParser::$updates, array_keys($payload)))) {
                return true;
            }
        }

        return false;
    }

    /**
     * verify user id
     *
     * @return bool
     */
    private function verifyUserId(): bool
    {
        $payload = Inbound::context();
        unset($payload['update_id']);
        $keys = array_keys($payload);
        $userId = $payload[$keys[0]]['from']['id'];

        $whitelist = self::$config['users']['whitelist'];
        $blacklist = self::$config['users']['blacklist'];

        if (!empty($whitelist)) return in_array($userId, $whitelist);
        if (!empty($blacklist)) return !in_array($userId, $blacklist);

        return true;
    }

}