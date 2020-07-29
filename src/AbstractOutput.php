<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels;

abstract class AbstractOutput implements OutputInterface
{
    private ConfigurationInterface $configuration;

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getConfiguration(): ConfigurationInterface
    {
        return $this->configuration;
    }

    public function getData(): array
    {
        return [
            'config' => $this->configuration->getData(),
        ];
    }
}
