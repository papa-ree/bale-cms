<?php

namespace Paparee\BaleCms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Paparee\BaleCms\BaleCms
 */
class BaleCms extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Paparee\BaleCms\BaleCms::class;
    }
}
