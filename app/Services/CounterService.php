<?php

namespace App\Services;

use App\Contracts\CounterContract;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Contracts\Session\Session;

class CounterService implements CounterContract
{
    private $timeout;
    private $cache;
    private $session;

    public function __construct(Cache $cache, Session $session, int $timeout)
    {
        $this->session = $session;
        $this->cache = $cache;
        $this->timeout = $timeout;
    }

    public function getCurrentUserViewCount(string $key) : int
    {
        $sessionId = $this->session->getId();
        $counterKey = "{$key}-counter";
        $usersKey = "{$key}-users";
        
        $users = $this->cache->get($usersKey, []);
        $usersUpdate = [];
        $difference = 0;
        $now = now();
       
        foreach($users as $session => $lastVisit) {
            if($now->diffInMinutes($lastVisit) >= $this->timeout) {
                $difference--;
            } else {
                $usersUpdate[$session] = $lastVisit;
            }
        }

        if(!array_key_exists($sessionId, $users) || $now->diffInMinutes($users[$sessionId]) >= $this->timeout) {
            $difference++;
        }

        $usersUpdate[$sessionId] = $now;

        $this->cache->forever($usersKey, $usersUpdate);

        if(!$this->cache->has($counterKey)) {
            $this->cache->forever($counterKey, 1);
        } else {
            $this->cache->increment($counterKey, $difference);
        }

        return $this->cache->get($counterKey);
    }
}