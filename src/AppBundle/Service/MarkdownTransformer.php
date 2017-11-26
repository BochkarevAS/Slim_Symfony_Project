<?php

namespace AppBundle\Service;

use Doctrine\Common\Cache\Cache;

class MarkdownTransformer {

    private $cache;

    public function __construct(Cache $cache) {
        $this->cache = $cache;
    }

    public function parse($str) {
        $cache = $this->cache;
        $key = md5($str);

        if ($cache->contains($key)) {
            sleep(1);
            return $cache->fetch($key);
        }

        return $cache->save($key, $str);
    }
}