<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels;

interface ConfigurationInterface
{
    public function getSource(): string;

    public function getTarget(): string;

    public function getBaseClass(): string;

    public function validate(): int;

    /**
     * @return array{source:string, target:string, base-class:string}
     */
    public function toArray(): array;

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): self;
}
