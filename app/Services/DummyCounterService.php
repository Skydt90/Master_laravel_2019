<?php

namespace App\Services;

use App\Contracts\CounterContract;

class DummyCounterService implements CounterContract
{
    public function getCurrentUserViewCount(string $key) : int
    {
        dd("I'm a dummy counter not implemented yet");
        return 0;
    }
}