<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels;

class TestManifestCollection implements OutputInterface
{
    /**
     * @var TestManifest[]
     */
    private array $testManifests = [];

    /**
     * @param array<mixed> $testManifests
     */
    public function __construct(array $testManifests)
    {
        foreach ($testManifests as $testManifest) {
            if ($testManifest instanceof TestManifest) {
                $this->testManifests[] = $testManifest;
            }
        }
    }

    /**
     * @return TestManifest[]
     */
    public function getManifests(): array
    {
        return $this->testManifests;
    }

    public function toArray(): array
    {
        $data = [];

        foreach ($this->testManifests as $testManifest) {
            $data[] = $testManifest->toArray();
        }

        return $data;
    }

    public static function fromArray(array $data): TestManifestCollection
    {
        $testManifests = [];

        foreach ($data as $value) {
            if (is_array($value)) {
                $testManifest = TestManifest::fromArray($value);

                if (TestManifest::VALIDATION_STATE_VALID === $testManifest->validate()) {
                    $testManifests[] = $testManifest;
                }
            }
        }

        return new TestManifestCollection($testManifests);
    }
}
