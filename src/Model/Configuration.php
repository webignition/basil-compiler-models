<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Model;

class Configuration implements ConfigurationInterface
{
    public function __construct(
        private readonly string $source,
        private readonly string $target,
        private readonly string $baseClass
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

    public function getBaseClass(): string
    {
        return $this->baseClass;
    }

    public function toArray(): array
    {
        return [
            'source' => $this->source,
            'target' => $this->target,
            'base-class' => $this->baseClass,
        ];
    }
}
