<?php

namespace Karamel\Cache\Drivers;

use Karamel\Cache\Exceptions\CachePathPermissionDeniedException;
use Karamel\Cache\Interfaces\ICache;

class File implements ICache
{
    private $host;
    private $port;
    private $prefix;


    public function __construct($host, $port, $prefix = null)
    {

        $this->host = $host;
        $this->port = $port;
        $this->prefix = $prefix;
    }

    public function connect()
    {
        $isCreatedDirectory = true;
        if (!file_exists($this->host))
            $isCreatedDirectory = @mkdir($this->host);

        if ($isCreatedDirectory === false)
            throw new CachePathPermissionDeniedException();

    }

    private function getCachePath($filename = null)
    {
        if (strpos($this->host, '/') == (strlen($this->host) - 1))
            return $filename === null ? $this->host : $this->host . $filename;
        return $filename === null ? $this->host . '/' : $this->host . '/' . $filename;
    }

    private function getCacheKey($key)
    {
        return 'karamel_cache_' . md5($key) . '-' . md5($this->prefix . $key);
    }

    public function get($key, $default = null)
    {
        $cache_key = $this->getCacheKey($key);
        if (!file_exists($this->getCachePath($cache_key)))
            return $default;

        $data = unserialize(file_get_contents($this->getCachePath($cache_key)));

  /*      if ($data['expire'] < time()) {
            $this->del($key);
            return $default;
        }*/

        return unserialize($data['content']);

    }

    public function set($key, $value, $expire = null)
    {
        $cache_key = $this->getCacheKey($key);
        $file = fopen($this->getCachePath($cache_key), 'w+');

        $data = [
            'expire' => time() + $expire,
            'content' => serialize($value)
        ];

        fwrite($file, serialize($data));
        fclose($file);

        return true;
    }

    public function del($key)
    {
        $cache_key = $this->getCacheKey($key);
        if (!file_exists($this->getCachePath($cache_key)))
            return true;

        @unlink($this->getCachePath($cache_key));
        return true;
    }

    public function garbageCollector()
    {
    }
}