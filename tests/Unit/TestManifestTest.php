<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Tests\Unit;

use PHPUnit\Framework\TestCase;
use webignition\BasilCompilerModels\TestManifest;
use webignition\BasilModels\Test\Configuration as TestConfiguration;
use webignition\BasilModels\Test\ConfigurationInterface as TestConfigurationInterface;

class TestManifestTest extends TestCase
{
    private const SOURCE = 'test.yml';
    private const TARGET = 'GeneratedTest.php';
    private const STEP_COUNT = 9;

    private TestManifest $manifest;
    private TestConfigurationInterface $configuration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->configuration = new TestConfiguration('chrome', 'http://example.com');
        $this->manifest = new TestManifest($this->configuration, self::SOURCE, self::TARGET, self::STEP_COUNT);
    }

    public function testGetSource(): void
    {
        self::assertSame(self::SOURCE, $this->manifest->getSource());
    }

    public function testGetTarget(): void
    {
        self::assertSame(self::TARGET, $this->manifest->getTarget());
    }

    public function testGetConfiguration(): void
    {
        self::assertSame($this->configuration, $this->manifest->getConfiguration());
    }

    public function testGetStepCount(): void
    {
        self::assertSame(self::STEP_COUNT, $this->manifest->getStepCount());
    }

    public function testGetData(): void
    {
        self::assertSame(
            [
                'config' => [
                    'browser' => 'chrome',
                    'url' => 'http://example.com',
                ],
                'source' => self::SOURCE,
                'target' => self::TARGET,
                'step_count' => self::STEP_COUNT,
            ],
            $this->manifest->getData()
        );
    }

    public function testFromArray(): void
    {
        self::assertEquals(
            new TestManifest($this->configuration, self::SOURCE, self::TARGET, self::STEP_COUNT),
            TestManifest::fromArray([
                'config' => [
                    'browser' => 'chrome',
                    'url' => 'http://example.com',
                ],
                'source' => self::SOURCE,
                'target' => self::TARGET,
                'step_count' => self::STEP_COUNT,
            ])
        );
    }

    /**
     * @dataProvider validateDataProvider
     */
    public function testValidate(TestManifest $testManifest, int $expectedValidationState): void
    {
        self::assertSame($expectedValidationState, $testManifest->validate());
    }

    /**
     * @return array<mixed>
     */
    public function validateDataProvider(): array
    {
        return [
            'configuration invalid' => [
                'testManifest' => new TestManifest(
                    new TestConfiguration('', ''),
                    'source',
                    'target',
                    self::STEP_COUNT
                ),
                'expectedValidationState' => TestManifest::VALIDATION_STATE_CONFIGURATION_INVALID,
            ],
            'source empty' => [
                'testManifest' => new TestManifest(
                    new TestConfiguration('chrome', 'http://example.com'),
                    '',
                    'target',
                    self::STEP_COUNT
                ),
                'expectedValidationState' => TestManifest::VALIDATION_STATE_SOURCE_EMPTY,
            ],
            'target empty' => [
                'testManifest' => new TestManifest(
                    new TestConfiguration('chrome', 'http://example.com'),
                    'source',
                    '',
                    self::STEP_COUNT
                ),
                'expectedValidationState' => TestManifest::VALIDATION_STATE_TARGET_EMPTY,
            ],
            'valid' => [
                'testManifest' => new TestManifest(
                    new TestConfiguration('chrome', 'http://example.com'),
                    'source',
                    'target',
                    self::STEP_COUNT
                ),
                'expectedValidationState' => TestManifest::VALIDATION_STATE_VALID,
            ],
        ];
    }
}
