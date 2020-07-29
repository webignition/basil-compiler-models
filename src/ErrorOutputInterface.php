<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels;

interface ErrorOutputInterface extends OutputInterface
{
    public function getCode(): int;
}
