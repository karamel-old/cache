<?php
namespace Karamel\Cache\Interfaces;

interface ICacheFactory{
    public static function build($type,$host,$port,$prefix=null);
}