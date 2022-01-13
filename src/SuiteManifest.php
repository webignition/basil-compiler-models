<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels;

class SuiteManifest extends AbstractOutput
{
    public const VALIDATION_STATE_VALID = 1;
    public const VALIDATION_STATE_CONFIGURATION_INVALID = 2;
    public const VALIDATION_STATE_TEST_MANIFEST_INVALID = 3;

    /**
     * @var TestManifest[]
     */
    private array $testManifests;

    /**
     * @param TestManifest[] $manifests
     */
    public function __construct(ConfigurationInterface $configuration, array $manifests = [])
    {
        parent::__construct($configuration);

        $this->testManifests = array_filter($manifests, function ($item) {
            return $item instanceof TestManifest;
        });
    }

    /**
     * @return TestManifest[]
     */
    public function getTestManifests(): array
    {
        return $this->testManifests;
    }

    public function validate(): int
    {
        if (Configuration::VALIDATION_STATE_VALID !== $this->getConfiguration()->validate()) {
            return self::VALIDATION_STATE_CONFIGURATION_INVALID;
        }

        foreach ($this->testManifests as $testManifest) {
            if ($testManifest instanceof TestManifest) {
                if (TestManifest::VALIDATION_STATE_VALID !== $testManifest->validate()) {
                    return self::VALIDATION_STATE_TEST_MANIFEST_INVALID;
                }
            }
        }

        return self::VALIDATION_STATE_VALID;
    }

    /**
     * @return array<mixed>
     */
    public function getData(): array
    {
        $manifests = [];
        foreach ($this->testManifests as $testManifest) {
            $manifests[] = $testManifest->getData();
        }

        $serializedData = parent::getData();
        $serializedData['manifests'] = $manifests;

        return $serializedData;
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): SuiteManifest
    {
        $configData = $data['config'] ?? [];
        $manifestsData = $data['manifests'] ?? [];

        $testManifests = [];
        foreach ($manifestsData as $manifestData) {
            $testManifests[] = TestManifest::fromArray($manifestData);
        }

        return new SuiteManifest(Configuration::fromArray($configData), $testManifests);
    }
}
