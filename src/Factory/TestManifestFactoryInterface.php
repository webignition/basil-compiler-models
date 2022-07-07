<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Factory;

use webignition\BasilCompilerModels\Exception\InvalidTestManifestException;
use webignition\BasilCompilerModels\Model\TestManifest;

interface TestManifestFactoryInterface
{
    /**
     * @param array<mixed> $data
     *
     * @throws InvalidTestManifestException
     */
    public function create(array $data): TestManifest;
}
