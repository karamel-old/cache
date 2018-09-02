<?php
namespace Karamel\Cache\Interfaces;
interface ICache{
    public function __construct($host,$port,$prefix=null);
    public function connect();
    public function get($key,$default=null);
    public function set($key,$value,$expire=null);
    public function del($key);
    public function garbageCollector();
}