<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Model;

interface ConfigurationInterface
{
    public function getSource(): string;

    public function getTarget(): string;

    public function getBaseClass(): string;

    /**
     * @return array{source:string, target:string, base-class:string}
     */
    public function toArray(): array;
}
