<?php

namespace ForceCMS\Exception;

class PermissionException extends \Exception
{
    public static function denied()
    {
        return new self('Permission denied.');
    }
}