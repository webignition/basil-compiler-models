<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Exception;

class InvalidTestManifestException extends \Exception
{
    public const CODE_CONFIG_BROWSER_EMPTY = 100;
    public const CODE_CONFIG_URL_EMPTY = 101;
    public const CODE_SOURCE_EMPTY = 200;
    public const CODE_TARGET_EMPTY = 200;

    public static function createForEmptyBrowser(): InvalidTestManifestException
    {
        return new InvalidTestManifestException('config.browser empty', self::CODE_CONFIG_BROWSER_EMPTY);
    }

    public static function createForEmptyUrl(): InvalidTestManifestException
    {
        return new InvalidTestManifestException('config.url empty', self::CODE_CONFIG_URL_EMPTY);
    }

    public static function createForEmptySource(): InvalidTestManifestException
    {
        return new InvalidTestManifestException('source empty', self::CODE_SOURCE_EMPTY);
    }

    public static function createForEmptyTarget(): InvalidTestManifestException
    {
        return new InvalidTestManifestException('source empty', self::CODE_TARGET_EMPTY);
    }
}
