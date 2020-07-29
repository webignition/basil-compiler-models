<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels;

interface ConfigurationInterface
{
    public function getSource(): string;
    public function getTarget(): string;
    public function getBaseClass(): string;

    /**
     * @return array<string, string>
     */
    public function getData(): array;

    /**
     * @param array<mixed> $data
     *
     * @return self
     */
    public static function fromArray(array $data): self;
}
