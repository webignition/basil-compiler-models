<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Exception;

class InvalidTestManifestException extends \Exception
{
    public const CODE_CONFIG_BROWSER_EMPTY = 100;

    public static function createForEmptyBrowser(): InvalidTestManifestException
    {
        return new InvalidTestManifestException('config.browser empty', self::CODE_CONFIG_BROWSER_EMPTY);
    }
}
