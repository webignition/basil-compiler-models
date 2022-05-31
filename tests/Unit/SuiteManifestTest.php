<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Tests\Unit;

use Mockery;
use PHPUnit\Framework\TestCase;
use webignition\BasilCompilerModels\Configuration;
use webignition\BasilCompilerModels\ConfigurationInterface;
use webignition\BasilCompilerModels\SuiteManifest;
use webignition\BasilCompilerModels\TestManifest;
use webignition\BasilModels\Model\Test\Configuration as TestConfiguration;
use webignition\BasilModels\Model\Test\Configuration as TestModelConfiguration;

class SuiteManifestTest extends TestCase
{
    private const SUITE_SOURCE = '/source';
    private const SUITE_TARGET = '/target';

    public function testGetConfiguration(): void
    {
        $suiteConfiguration = $this->createSuiteConfiguration();
        $suiteManifest = new SuiteManifest($suiteConfiguration);

        self::assertSame($suiteConfiguration, $suiteManifest->getConfiguration());
    }

    public function testGetTestManifests(): void
    {
        $testManifests = [
            new TestManifest(
                new TestModelConfiguration('chrome', 'http://example.com'),
                self::SUITE_SOURCE . '/test1.yml',
                self::SUITE_TARGET . '/GeneratedTest1.php',
                ['step 1']
            ),
            new TestManifest(
                new TestModelConfiguration('firefox', 'http://example.com'),
                self::SUITE_SOURCE . '/test2.yml',
                self::SUITE_TARGET . '/GeneratedTest2.php',
                ['step 1', 'step 2']
            ),
        ];

        $suiteConfiguration = $this->createSuiteConfiguration();
        $suiteManifest = new SuiteManifest($suiteConfiguration, $testManifests);

        self::assertSame($testManifests, $suiteManifest->getTestManifests());
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param array<mixed> $expectedData
     */
    public function testGetData(SuiteManifest $suiteManifest, array $expectedData): void
    {
        self::assertSame($expectedData, $suiteManifest->getData());
    }

    /**
     * @return array<mixed>
     */
    public function getDataDataProvider(): array
    {
        $suiteConfiguration = $this->createSuiteConfiguration();
        $testManifests = [
            new TestManifest(
                new TestModelConfiguration('chrome', 'http://example.com'),
                self::SUITE_SOURCE . '/test1.yml',
                self::SUITE_TARGET . '/GeneratedTest1.php',
                ['step 1', 'step 2', 'step 3']
            ),
            new TestManifest(
                new TestModelConfiguration('firefox', 'http://example.com'),
                self::SUITE_SOURCE . '/test2.yml',
                self::SUITE_TARGET . '/GeneratedTest2.php',
                ['step 1', 'step 2', 'step 3', 'step 4']
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
                            'step_names' => ['step 1', 'step 2', 'step 3']
                        ],
                        [
                            'config' => [
                                'browser' => 'firefox',
                                'url' => 'http://example.com',
                            ],
                            'source' => self::SUITE_SOURCE . '/test2.yml',
                            'target' => self::SUITE_TARGET . '/GeneratedTest2.php',
                            'step_names' => ['step 1', 'step 2', 'step 3', 'step 4']
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataFromArrayDataProvider
     */
    public function testGetDataFromArray(SuiteManifest $output): void
    {
        self::assertEquals(
            $output,
            SuiteManifest::fromArray($output->getData())
        );
    }

    /**
     * @return array<mixed>
     */
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
                        self::SUITE_TARGET . '/GeneratedTest1.php',
                        ['step 1', 'step 2', 'step 3', 'step 4', 'step 5']
                    ),
                    new TestManifest(
                        $testConfiguration,
                        self::SUITE_SOURCE . '/test2.yml',
                        self::SUITE_TARGET . '/GeneratedTest2.php',
                        ['step 1', 'step 2', 'step 3', 'step 4', 'step 5', 'step 6']
                    ),
                    new TestManifest(
                        $testConfiguration,
                        self::SUITE_SOURCE . '/test3.yml',
                        self::SUITE_TARGET . '/GeneratedTest3.php',
                        ['step 1', 'step 2', 'step 3', 'step 4', 'step 5', 'step 6', 'step 7']
                    ),
                ]),
            ],
        ];
    }

    /**
     * @dataProvider validateDataProvider
     */
    public function testValidate(SuiteManifest $suiteManifest, int $expectedValidationState): void
    {
        self::assertSame($expectedValidationState, $suiteManifest->validate());
    }

    /**
     * @return array<mixed>
     */
    public function validateDataProvider(): array
    {
        $invalidConfiguration = Mockery::mock(ConfigurationInterface::class);
        $invalidConfiguration
            ->shouldReceive('validate')
            ->andReturn(Configuration::VALIDATION_STATE_SOURCE_NOT_READABLE)
        ;

        $validConfiguration = Mockery::mock(ConfigurationInterface::class);
        $validConfiguration
            ->shouldReceive('validate')
            ->andReturn(Configuration::VALIDATION_STATE_VALID)
        ;

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
                            '',
                            ['step 1', 'step 2', 'step 3', 'step 4', 'step 5', 'step 6', 'step 7', 'step 8']
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
                            self::SUITE_TARGET . '/GeneratedTest.php',
                            ['step 1', 'step 2', 'step 3', 'step 4', 'step 5', 'step 6', 'step 7', 'step 8', 'step 9']
                        ),
                    ]
                ),
                'expectedValidationState' => suiteManifest::VALIDATION_STATE_VALID,
            ],
        ];
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
