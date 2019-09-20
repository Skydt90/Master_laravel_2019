<?php

namespace App\Contracts;

interface CounterContract 
{
    public function getCurrentUserViewCount(string $key) : int;
}