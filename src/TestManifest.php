<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels;

use webignition\BasilModels\Test\Configuration as TestConfiguration;
use webignition\BasilModels\Test\ConfigurationInterface as TestConfigurationInterface;

class TestManifest
{
    public const VALIDATION_STATE_VALID = 1;
    public const VALIDATION_STATE_CONFIGURATION_INVALID = 2;
    public const VALIDATION_STATE_SOURCE_EMPTY = 3;
    public const VALIDATION_STATE_TARGET_EMPTY = 4;

    public function __construct(
        private readonly TestConfigurationInterface $configuration,
        private readonly string $source,
        private readonly string $target,
        private readonly int $stepCount
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

    public function getConfiguration(): TestConfigurationInterface
    {
        return $this->configuration;
    }

    public function getStepCount(): int
    {
        return $this->stepCount;
    }

    public function validate(): int
    {
        if (TestConfigurationInterface::VALIDATION_STATE_VALID !== $this->configuration->validate()) {
            return self::VALIDATION_STATE_CONFIGURATION_INVALID;
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
                'browser' => $this->configuration->getBrowser(),
                'url' => $this->configuration->getUrl(),
            ],
            'source' => $this->source,
            'target' => $this->target,
            'step_count' => $this->stepCount,
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

        $stepCount = $data['step_count'] ?? 0;
        $stepCount = is_int($stepCount) ? $stepCount : 0;

        return new TestManifest(
            new TestConfiguration($configData['browser'] ?? '', $configData['url'] ?? ''),
            $source,
            $target,
            $stepCount
        );
    }
}
