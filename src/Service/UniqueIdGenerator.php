<?php

declare(strict_types=1);

namespace App\Service;

class UniqueIdGenerator
{
    public function __invoke():string
    {
        return \uniqid();
    }
}
