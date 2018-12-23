<?php

namespace Karamel\Cache\Facade;

use Karamel\Cache\CacheFactory;

class Cache
{
    private static $instance;
    private static $host;
    private static $port;
    private static $prefix;
    private static $type;


    public static function setConfig($type, $host, $port, $prefix = null)
    {
        self::$type = $type;
        self::$host = $host;
        self::$port = $port;
        self::$prefix = $prefix;
    }

    public static function get($key)
    {
        return self::getInstance()->get($key);
    }

    public static function getInstance()
    {
        if (self::$instance != null)
            return self::$instance;

        if (self::$host == null || self::$port === null || self::$type === null)
            throw new \Karamel\Cache\Exceptions\FacadeErrorSetConfig();

        self::$instance = CacheFactory::build(self::$type, self::$host, self::$port, self::$prefix);
        return self::$instance;

    }

    public static function set($key, $value, $expire)
    {
        return self::getInstance()->set($key, $value, $expire);
    }

    public static function del($key)
    {
        return self::getInstance()->del($key);
    }


}