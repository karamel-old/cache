<?php

namespace Karamel\Cache\Drivers;

use Karamel\Cache\Exceptions\CacheDriverConnectionFailedException;
use Karamel\Cache\Interfaces\ICache;

class Memcache implements ICache
{
    private $host;
    private $port;
    private $prefix;
    private $connection = null;

    public function __construct($host, $port, $prefix = null)
    {
        $this->host = $host;
        $this->port = $port;
        $this->prefix = $prefix;
    }

    /**
     * @return \Memcache|null
     * @throws CacheDriverConnectionFailedException
     */
    public function connect()
    {
        if ($this->connection !== null)
            return $this->connection;

        $connection = new \Memcache();
        if (!$connection->connect($this->host, $this->port))
            throw new CacheDriverConnectionFailedException();

        $this->connection = $connection;
        return $this->connection;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     * @throws CacheDriverConnectionFailedException
     */
    public function get($key, $default = null)
    {
        $value = $this->connect()->get($this->prefix . $key);
        return $value == false ? $default : unserialize($value);
    }

    /**
     * @param $key
     * @param $value
     * @param null $expire
     * @return bool
     * @throws CacheDriverConnectionFailedException
     */
    public function set($key, $value, $expire = null)
    {
        $value = serialize($value);
        return $this->connect()->set($this->prefix . $key, $value, null, $expire);
    }

    /**
     * @param $key
     * @return bool
     * @throws CacheDriverConnectionFailedException
     */
    public function del($key)
    {
        return $this->connect()->delete($this->prefix . $key);
    }

    public function garbageCollector()
    {
        // TODO: Implement garbageCollector() method.
    }
}