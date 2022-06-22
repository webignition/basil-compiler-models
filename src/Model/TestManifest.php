<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Model;

class TestManifest
{
    public const VALIDATION_STATE_VALID = 1;

    /**
     * @param non-empty-string   $browser
     * @param non-empty-string   $url
     * @param non-empty-string   $source
     * @param non-empty-string   $target
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

    /**
     * @return non-empty-string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @return non-empty-string
     */
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

    /**
     * @return non-empty-string
     */
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

    /**
     * @return array{
     *     config: array{browser: non-empty-string, url: non-empty-string},
     *     source: non-empty-string,
     *     target: non-empty-string,
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
