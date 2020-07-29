<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels;

use webignition\BasilModels\Test\Configuration as TestConfiguration;
use webignition\BasilModels\Test\ConfigurationInterface as TestConfigurationInterface;

class TestManifest
{
    private string $source;
    private string $target;
    private TestConfigurationInterface $configuration;

    public function __construct(TestConfigurationInterface $configuration, string $source, string $target)
    {
        $this->configuration = $configuration;
        $this->source = $source;
        $this->target = $target;
    }

    public function getTarget(): string
    {
        return $this->target;
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
            $data['target']
        );
    }
}
