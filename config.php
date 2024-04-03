<?php

namespace TeleBot\Config;

/**
 * @var array $config configuration properties
 */
return [

    /**
     * @var string $domain application domain name
     */
    'domain' => getenv('APP_DOMAIN', true),

    /**
     * @param string $token property holding bot token
     */
    'token' => getenv('TG_BOT_TOKEN', true),

    /**
     * @var string $ip property holding source IP
     */
    'ip' => getenv('TG_SOURCE_IP', true),

    /**
     * @var array $routes allowed routes
     */
    'routes' => [],

    /**
     * @var array $authorization property holding request signature
     */
    'signature' => getenv('TG_BOT_SIGNATURE', true),

    /**
     * @var array $users allowed users
     */
    'users' => [

        /**
         * whitelisted users. Leave empty to accept all
         */
        'whitelist' => [],

        /**
         * blacklisted users. Leave empty to accept all
         * this will be overlooked if *whitelist* is not empty
         */
        'blacklist' => [],
    ],

];