<?php

namespace Karamel\Cache;

use Karamel\Cache\Drivers\File;
use Karamel\Cache\Drivers\Memcache;
use Karamel\Cache\Drivers\Redis;
use Karamel\Cache\Exceptions\CacheDriverClassNotFoundException;
use Karamel\Cache\Interfaces\ICacheFactory;

class CacheFactory implements ICacheFactory
{
    /**
     * @param $type  file | database | memcache | redis
     * @return mixed
     * @throws CacheDriverClassNotFoundException
     */
    public static function build($type, $host, $port, $prefix = null)
    {
        $type = ucwords($type);
        /*if (!class_exists($type))
            throw new CacheDriverClassNotFoundException();*/

        $class = null;
        switch ($type) {
            case 'File':
                return new File($host, $port, $prefix);
                break;
            case 'Memcache':
                return new Memcache($host, $port, $prefix);
                break;
            case 'Redis':
                return new Redis($host, $port, $prefix);
            default:
                throw new CacheDriverClassNotFoundException();
        }
    }
}