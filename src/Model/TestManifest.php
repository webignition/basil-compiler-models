<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Model;

class TestManifest
{
    public const VALIDATION_STATE_VALID = 1;
    public const VALIDATION_STATE_SOURCE_EMPTY = 3;
    public const VALIDATION_STATE_TARGET_EMPTY = 4;
    public const VALIDATION_STATE_URL_EMPTY = 6;

    /**
     * @param non-empty-string   $browser
     * @param non-empty-string[] $stepNames
     */
    public function __construct(
        private readonly string $browser,
        private readonly string $url,
        private readonly string $source,
        private readonly string $target,
        private readonly array $stepNames,
    ) {
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @return non-empty-string
     */
    public function getBrowser(): string
    {
        return $this->browser;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return non-empty-string[]
     */
    public function getStepNames(): array
    {
        return $this->stepNames;
    }

    public function validate(): int
    {
        if ('' === trim($this->url)) {
            return self::VALIDATION_STATE_URL_EMPTY;
        }

        if ('' === trim($this->source)) {
            return self::VALIDATION_STATE_SOURCE_EMPTY;
        }

        if ('' === trim($this->target)) {
            return self::VALIDATION_STATE_TARGET_EMPTY;
        }

        return self::VALIDATION_STATE_VALID;
    }

    /**
     * @return array{
     *     config: array{browser: non-empty-string, url: string},
     *     source: string,
     *     target: string,
     *     step_names: non-empty-string[]
     * }
     */
    public function toArray(): array
    {
        return [
            'config' => [
                'browser' => $this->browser,
                'url' => $this->url,
            ],
            'source' => $this->source,
            'target' => $this->target,
            'step_names' => $this->stepNames,
        ];
    }
}
