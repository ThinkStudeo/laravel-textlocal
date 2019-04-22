<?php

namespace Thinkstudeo\Textlocal\Exceptions;

use Exception;

class AuthenticationException extends Exception
{
    public static function apiKeyMissing($accountType)
    {
        return new static(
            "No record found for textlocal.{$accountType}.apiKey in config"
        );
    }
}
