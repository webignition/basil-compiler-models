<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Model;

interface ErrorOutputInterface extends OutputInterface
{
    public function getCode(): int;
}
