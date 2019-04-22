<?php

namespace Thinkstudeo\Textlocal\Exceptions;

use Exception;

class ApiRequestFailure extends Exception
{
    public static function requestRespondedWithError($message, $code)
    {
        return new static (
            "Textlocal responded with error '{$message}: {$code}'"
        );
    }
}
