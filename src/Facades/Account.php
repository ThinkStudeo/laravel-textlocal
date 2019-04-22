<?php

namespace Thinkstudeo\Textlocal\Facades;

use Illuminate\Support\Facades\Facade;

class Account extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'accountClient';
    }
}
