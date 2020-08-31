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

    private TestManifest $manifest;
    private TestConfigurationInterface $configuration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->configuration = new TestConfiguration('chrome', 'http://example.com');
        $this->manifest = new TestManifest($this->configuration, self::SOURCE, self::TARGET);
    }

    public function testGetSource()
    {
        self::assertSame(self::SOURCE, $this->manifest->getSource());
    }

    public function testGetTarget()
    {
        self::assertSame(self::TARGET, $this->manifest->getTarget());
    }

    public function testGetConfiguration()
    {
        self::assertSame($this->configuration, $this->manifest->getConfiguration());
    }

    public function testGetData()
    {
        self::assertSame(
            [
                'config' => [
                    'browser' => 'chrome',
                    'url' => 'http://example.com',
                ],
                'source' => self::SOURCE,
                'target' => self::TARGET,
            ],
            $this->manifest->getData()
        );
    }

    public function testFromArray()
    {
        self::assertEquals(
            new TestManifest($this->configuration, self::SOURCE, self::TARGET),
            TestManifest::fromArray([
                'config' => [
                    'browser' => 'chrome',
                    'url' => 'http://example.com',
                ],
                'source' => self::SOURCE,
                'target' => self::TARGET,
            ])
        );
    }

    /**
     * @dataProvider validateDataProvider
     */
    public function testValidate(TestManifest $testManifest, int $expectedValidationState)
    {
        self::assertSame($expectedValidationState, $testManifest->validate());
    }

    public function validateDataProvider(): array
    {
        return [
            'configuration invalid' => [
                'testManifest' => new TestManifest(
                    new TestConfiguration('', ''),
                    'source',
                    'target'
                ),
                'expectedValidationState' => TestManifest::VALIDATION_STATE_CONFIGURATION_INVALID,
            ],
            'source empty' => [
                'testManifest' => new TestManifest(
                    new TestConfiguration('chrome', 'http://example.com'),
                    '',
                    'target'
                ),
                'expectedValidationState' => TestManifest::VALIDATION_STATE_SOURCE_EMPTY,
            ],
            'target empty' => [
                'testManifest' => new TestManifest(
                    new TestConfiguration('chrome', 'http://example.com'),
                    'source',
                    ''
                ),
                'expectedValidationState' => TestManifest::VALIDATION_STATE_TARGET_EMPTY,
            ],
            'valid' => [
                'testManifest' => new TestManifest(
                    new TestConfiguration('chrome', 'http://example.com'),
                    'source',
                    'target'
                ),
                'expectedValidationState' => TestManifest::VALIDATION_STATE_VALID,
            ],
        ];
    }
}
