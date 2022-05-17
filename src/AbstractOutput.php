<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels;

abstract class AbstractOutput implements OutputInterface
{
    public function __construct(
        private readonly ConfigurationInterface $configuration
    ) {
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
