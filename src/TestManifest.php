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

    private string $source;
    private string $target;
    private TestConfigurationInterface $configuration;
    private int $stepCount;

    public function __construct(
        TestConfigurationInterface $configuration,
        string $source,
        string $target,
        int $stepCount
    ) {
        $this->configuration = $configuration;
        $this->source = $source;
        $this->target = $target;
        $this->stepCount = $stepCount;
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
     *
     * @return TestManifest
     */
    public static function fromArray(array $data): TestManifest
    {
        return new TestManifest(
            new TestConfiguration(
                $data['config']['browser'],
                $data['config']['url']
            ),
            $data['source'],
            $data['target'],
            $data['step_count']
        );
    }
}
