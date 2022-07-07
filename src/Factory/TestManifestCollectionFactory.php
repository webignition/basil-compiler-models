<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Factory;

use webignition\BasilCompilerModels\Exception\InvalidTestManifestException;
use webignition\BasilCompilerModels\Model\TestManifestCollection;

class TestManifestCollectionFactory
{
    public function __construct(
        private readonly TestManifestFactoryInterface $testManifestFactory,
    ) {
    }

    /**
     * @param array<mixed> $data
     *
     * @throws InvalidTestManifestException
     */
    public function create(array $data): TestManifestCollection
    {
        $testManifests = [];

        foreach ($data as $value) {
            if (is_array($value)) {
                $testManifests[] = $this->testManifestFactory->create($value);
            }
        }

        return new TestManifestCollection($testManifests);
    }
}
