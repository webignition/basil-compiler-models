<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels;

use webignition\BasilModels\Test\ConfigurationInterface as TestConfigurationInterface;

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
     * @param ConfigurationInterface $configuration
     * @param TestManifest[] $manifests
     */
    public function __construct(ConfigurationInterface $configuration, array $manifests = [])
    {
        parent::__construct($configuration);

        $this->testManifests = array_filter($manifests, function ($item) {
            return $item instanceof TestManifest;
        });
    }

    public function createTestManifest(
        TestConfigurationInterface $testConfiguration,
        string $relativeSource,
        string $relativeTarget
    ): TestManifest {
        $suiteConfiguration = $this->getConfiguration();
        $testManifest = new TestManifest(
            $testConfiguration,
            $suiteConfiguration->getSource() . '/' . $relativeSource,
            $suiteConfiguration->getTarget() . '/' . $relativeTarget
        );

        $this->add($testManifest);

        return $testManifest;
    }

    public function add(TestManifest $testManifest): void
    {
        $this->testManifests[] = $testManifest;
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
     *
     * @return SuiteManifest
     */
    public static function fromArray(array $data): SuiteManifest
    {
        $configData = $data['config'] ?? [];
        $manifestsData = $data['manifests'] ?? [];

        $suiteManifest = new SuiteManifest(Configuration::fromArray($configData));

        foreach ($manifestsData as $manifestData) {
            $suiteManifest->add(TestManifest::fromArray($manifestData));
        }

        return $suiteManifest;
    }
}
