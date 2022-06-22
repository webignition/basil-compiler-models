<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Model;

interface OutputInterface
{
    /**
     * @return array<mixed>
     */
    public function toArray(): array;

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): self;
}
