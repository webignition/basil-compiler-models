<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Tests\Unit;

use Mockery;
use PHPUnit\Framework\TestCase;
use webignition\BasilCompilerModels\Configuration;
use webignition\BasilCompilerModels\ConfigurationInterface;
use webignition\BasilCompilerModels\SuiteManifest;
use webignition\BasilCompilerModels\TestManifest;
use webignition\BasilModels\Test\Configuration as TestConfiguration;
use webignition\BasilModels\Test\Configuration as TestModelConfiguration;

class SuiteManifestTest extends TestCase
{
    private const SUITE_SOURCE = '/source';
    private const SUITE_TARGET = '/target';

    public function testGetConfiguration()
    {
        $suiteConfiguration = $this->createSuiteConfiguration();
        $suiteManifest = new SuiteManifest($suiteConfiguration);

        self::assertSame($suiteConfiguration, $suiteManifest->getConfiguration());
    }

    public function testGetTestManifests()
    {
        $testManifests = [
            new TestManifest(
                new TestModelConfiguration('chrome', 'http://example.com'),
                self::SUITE_SOURCE . '/test1.yml',
                self::SUITE_TARGET . '/GeneratedTest1.php'
            ),
            new TestManifest(
                new TestModelConfiguration('firefox', 'http://example.com'),
                self::SUITE_SOURCE . '/test2.yml',
                self::SUITE_TARGET . '/GeneratedTest2.php'
            ),
        ];

        $suiteConfiguration = $this->createSuiteConfiguration();
        $suiteManifest = new SuiteManifest($suiteConfiguration, $testManifests);

        self::assertSame($testManifests, $suiteManifest->getTestManifests());
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param SuiteManifest $suiteManifest
     * @param array<mixed> $expectedData
     */
    public function testGetData(SuiteManifest $suiteManifest, array $expectedData)
    {
        self::assertSame($expectedData, $suiteManifest->getData());
    }

    public function getDataDataProvider(): array
    {
        $suiteConfiguration = $this->createSuiteConfiguration();
        $testManifests = [
            new TestManifest(
                new TestModelConfiguration('chrome', 'http://example.com'),
                self::SUITE_SOURCE . '/test1.yml',
                self::SUITE_TARGET . '/GeneratedTest1.php'
            ),
            new TestManifest(
                new TestModelConfiguration('firefox', 'http://example.com'),
                self::SUITE_SOURCE . '/test2.yml',
                self::SUITE_TARGET . '/GeneratedTest2.php'
            ),
        ];

        return [
            'empty generated test output collection' => [
                'suiteManifest' => new SuiteManifest($suiteConfiguration, []),
                'expectedData' => [
                    'config' => $suiteConfiguration->getData(),
                    'manifests' => [],
                ],
            ],
            'populated generated test output collection' => [
                'suiteManifest' => new SuiteManifest($suiteConfiguration, $testManifests),
                'expectedData' => [
                    'config' => $suiteConfiguration->getData(),
                    'manifests' => [
                        [
                            'config' => [
                                'browser' => 'chrome',
                                'url' => 'http://example.com',
                            ],
                            'source' => self::SUITE_SOURCE . '/test1.yml',
                            'target' => self::SUITE_TARGET . '/GeneratedTest1.php',
                        ],
                        [
                            'config' => [
                                'browser' => 'firefox',
                                'url' => 'http://example.com',
                            ],
                            'source' => self::SUITE_SOURCE . '/test2.yml',
                            'target' => self::SUITE_TARGET . '/GeneratedTest2.php',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataFromArrayDataProvider
     */
    public function testGetDataFromArray(SuiteManifest $output)
    {
        self::assertEquals(
            $output,
            SuiteManifest::fromArray($output->getData())
        );
    }

    public function getDataFromArrayDataProvider(): array
    {
        $suiteConfiguration = $this->createSuiteConfiguration();
        $testConfiguration = new TestModelConfiguration('chrome', 'http://example.com');

        return [
            'empty generated test output collection' => [
                'suiteManifest' => new SuiteManifest($suiteConfiguration, []),
            ],
            'populated generated test output collection' => [
                'suiteManifest' => new SuiteManifest($suiteConfiguration, [
                    new TestManifest(
                        $testConfiguration,
                        self::SUITE_SOURCE . '/test1.yml',
                        self::SUITE_TARGET . '/GeneratedTest1.php'
                    ),
                    new TestManifest(
                        $testConfiguration,
                        self::SUITE_SOURCE . '/test2.yml',
                        self::SUITE_TARGET . '/GeneratedTest2.php'
                    ),
                    new TestManifest(
                        $testConfiguration,
                        self::SUITE_SOURCE . '/test3.yml',
                        self::SUITE_TARGET . '/GeneratedTest3.php'
                    ),
                ]),
            ],
        ];
    }

    /**
     * @dataProvider validateDataProvider
     */
    public function testValidate(SuiteManifest $suiteManifest, int $expectedValidationState)
    {
        self::assertSame($expectedValidationState, $suiteManifest->validate());
    }

    public function validateDataProvider(): array
    {
        $invalidConfiguration = Mockery::mock(ConfigurationInterface::class);
        $invalidConfiguration
            ->shouldReceive('validate')
            ->andReturn(Configuration::VALIDATION_STATE_SOURCE_NOT_READABLE);

        $validConfiguration = Mockery::mock(ConfigurationInterface::class);
        $validConfiguration
            ->shouldReceive('validate')
            ->andReturn(Configuration::VALIDATION_STATE_VALID);

        return [
            'configuration invalid' => [
                'suiteManifest' => new SuiteManifest(
                    $invalidConfiguration,
                    []
                ),
                'expectedValidationState' => SuiteManifest::VALIDATION_STATE_CONFIGURATION_INVALID,
            ],
            'test manifest invalid' => [
                'suiteManifest' => new SuiteManifest(
                    $validConfiguration,
                    [
                        new TestManifest(
                            new TestConfiguration('', ''),
                            '',
                            ''
                        ),
                    ]
                ),
                'expectedValidationState' => SuiteManifest::VALIDATION_STATE_TEST_MANIFEST_INVALID,
            ],
            'valid' => [
                'suiteManifest' => new SuiteManifest(
                    $validConfiguration,
                    [
                        new TestManifest(
                            new TestConfiguration('chrome', 'http:;//example.com'),
                            self::SUITE_SOURCE . '/test.yml',
                            self::SUITE_TARGET . '/GeneratedTest.php'
                        ),
                    ]
                ),
                'expectedValidationState' => suiteManifest::VALIDATION_STATE_VALID,
            ],
        ];
    }

    public function testAdd()
    {
        $testManifest1 = new TestManifest(
            new TestConfiguration('chrome', 'http:;//example.com'),
            self::SUITE_SOURCE . '/test.yml',
            self::SUITE_TARGET . '/GeneratedTest.php'
        );

        $testManifest2 = new TestManifest(
            new TestConfiguration('firefox', 'http:;//example.com'),
            self::SUITE_SOURCE . '/test.yml',
            self::SUITE_TARGET . '/GeneratedTest.php'
        );

        $suiteConfiguration = $this->createSuiteConfiguration();
        $suiteManifest = new SuiteManifest($suiteConfiguration, [$testManifest1]);

        self::assertEquals([$testManifest1], $suiteManifest->getTestManifests());

        $suiteManifest->add($testManifest2);
        self::assertEquals([$testManifest1, $testManifest2], $suiteManifest->getTestManifests());
    }

    public function testCreateTestManifest()
    {
        $initialTestManifest = new TestManifest(
            new TestConfiguration('chrome', 'http:;//example.com'),
            self::SUITE_SOURCE . '/test.yml',
            self::SUITE_TARGET . '/GeneratedChromeTest.php'
        );

        $suiteConfiguration = $this->createSuiteConfiguration();
        $suiteManifest = new SuiteManifest($suiteConfiguration, [$initialTestManifest]);

        self::assertEquals([$initialTestManifest], $suiteManifest->getTestManifests());

        $addedTestManifest = $suiteManifest->createTestManifest(
            new TestModelConfiguration('firefox', 'http://example.com'),
            'test2.yml',
            'GeneratedFireFoxTest.php'
        );

        self::assertEquals(
            $addedTestManifest,
            new TestManifest(
                new TestModelConfiguration('firefox', 'http://example.com'),
                self::SUITE_SOURCE . '/test2.yml',
                self::SUITE_TARGET . '/GeneratedFireFoxTest.php'
            )
        );

        self::assertEquals([$initialTestManifest, $addedTestManifest], $suiteManifest->getTestManifests());
    }

    private function createSuiteConfiguration(): ConfigurationInterface
    {
        return new Configuration(
            self::SUITE_SOURCE,
            self::SUITE_TARGET,
            SuiteManifestTest::class
        );
    }
}
