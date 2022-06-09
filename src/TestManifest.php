<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels;

class TestManifest
{
    public const VALIDATION_STATE_VALID = 1;
    public const VALIDATION_STATE_CONFIGURATION_INVALID = 2;
    public const VALIDATION_STATE_SOURCE_EMPTY = 3;
    public const VALIDATION_STATE_TARGET_EMPTY = 4;
    public const VALIDATION_STATE_BROWSER_EMPTY = 5;
    public const VALIDATION_STATE_URL_EMPTY = 6;

    /**
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
        if ('' === trim($this->browser)) {
            return self::VALIDATION_STATE_BROWSER_EMPTY;
        }

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
     * @return array<string, mixed>
     */
    public function getData(): array
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

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): TestManifest
    {
        $configData = $data['config'] ?? [];
        $configData = is_array($configData) ? $configData : [];

        $source = $data['source'] ?? '';
        $source = is_string($source) ? $source : '';

        $target = $data['target'] ?? '';
        $target = is_string($target) ? $target : '';

        $stepNames = $data['step_names'] ?? [];
        $stepNames = is_array($stepNames) ? $stepNames : [];

        $filteredStepNames = [];
        foreach ($stepNames as $stepName) {
            if (is_string($stepName) && '' !== $stepName) {
                $filteredStepNames[] = $stepName;
            }
        }

        return new TestManifest(
            $configData['browser'] ?? '',
            $configData['url'] ?? '',
            $source,
            $target,
            $filteredStepNames
        );
    }
}
