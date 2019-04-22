<?php

namespace Thinkstudeo\Textlocal\Exceptions;

class InvalidInput
{
    public static function unacceptable($message, $code)
    {
        return new static("Input error: '{$message} [{$code}]");
    }
}
