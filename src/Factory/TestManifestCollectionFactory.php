<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Factory;

use webignition\BasilCompilerModels\Model\TestManifest;
use webignition\BasilCompilerModels\Model\TestManifestCollection;

class TestManifestCollectionFactory
{
    public function __construct(
        private readonly TestManifestFactory $testManifestFactory,
    ) {
    }

    /**
     * @param array<mixed> $data
     */
    public function create(array $data): TestManifestCollection
    {
        $testManifests = [];

        foreach ($data as $value) {
            if (is_array($value)) {
                $testManifest = $this->testManifestFactory->create($value);

                if (TestManifest::VALIDATION_STATE_VALID === $testManifest->validate()) {
                    $testManifests[] = $testManifest;
                }
            }
        }

        return new TestManifestCollection($testManifests);
    }
}
