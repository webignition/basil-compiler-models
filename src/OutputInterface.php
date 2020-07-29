<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels;

interface OutputInterface
{
    public function getConfiguration(): ConfigurationInterface;

    /**
     * @return array<mixed>
     */
    public function getData(): array;
}
