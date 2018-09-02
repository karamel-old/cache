<?php
    namespace Karamel\Cache;

    use Karamel\Cache\Drivers\File;

    if(CACHE_DRIVER == 'file'){
        class Cache extends File{}
    }