<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Exception;

class InvalidErrorOutputException extends \Exception
{
    public const CODE_MESSAGE_EMPTY = 100;

    public static function createForEmptyMessage(): InvalidErrorOutputException
    {
        return new InvalidErrorOutputException('message empty', self::CODE_MESSAGE_EMPTY);
    }
}
