<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Model;

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
}
