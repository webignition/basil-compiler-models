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
    private SuiteManifest $output;
    private ConfigurationInterface $configuration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->configuration = new Configuration('test.yml', 'build', SuiteManifestTest::class);
        $this->output = new SuiteManifest($this->configuration, []);
    }

    public function testGetConfiguration()
    {
        self::assertSame($this->configuration, $this->output->getConfiguration());
    }

    public function testGetOutput()
    {
        $testManifests = [
            new TestManifest(
                new TestModelConfiguration('chrome', 'http://example.com'),
                'test1.yml',
                'GeneratedTest1.php'
            ),
            new TestManifest(
                new TestModelConfiguration('firefox', 'http://example.com'),
                'test2.yml',
                'GeneratedTest2.php'
            ),
        ];

        $output = new SuiteManifest($this->configuration, $testManifests);
        self::assertSame($testManifests, $output->getTestManifests());
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param SuiteManifest $output
     * @param array<mixed> $expectedData
     */
    public function testGetData(SuiteManifest $output, array $expectedData)
    {
        self::assertSame($expectedData, $output->getData());
    }

    public function getDataDataProvider(): array
    {
        $configuration = new Configuration('test.yml', 'build', SuiteManifestTest::class);
        $testManifests = [
            new TestManifest(
                new TestModelConfiguration('chrome', 'http://example.com'),
                'test1.yml',
                'GeneratedTest1.php'
            ),
            new TestManifest(
                new TestModelConfiguration('firefox', 'http://example.com'),
                'test2.yml',
                'GeneratedTest2.php'
            ),
        ];

        return [
            'empty generated test output collection' => [
                'output' => new SuiteManifest($configuration, []),
                'expectedData' => [
                    'config' => $configuration->getData(),
                    'manifests' => [],
                ],
            ],
            'populated generated test output collection' => [
                'output' => new SuiteManifest($configuration, $testManifests),
                'expectedData' => [
                    'config' => $configuration->getData(),
                    'manifests' => [
                        [
                            'config' => [
                                'browser' => 'chrome',
                                'url' => 'http://example.com',
                            ],
                            'source' => 'test1.yml',
                            'target' => 'GeneratedTest1.php',
                        ],
                        [
                            'config' => [
                                'browser' => 'firefox',
                                'url' => 'http://example.com',
                            ],
                            'source' => 'test2.yml',
                            'target' => 'GeneratedTest2.php',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getTestPathsDataProvider
     *
     * @param SuiteManifest $suiteManifest
     * @param array<string> $expectedTestPaths
     */
    public function testGetTestPaths(SuiteManifest $suiteManifest, array $expectedTestPaths)
    {
        self::assertSame($expectedTestPaths, $suiteManifest->getTestPaths());
    }

    public function getTestPathsDataProvider(): array
    {
        $compilerConfiguration = new Configuration('test.yml', 'build', SuiteManifestTest::class);
        $testConfiguration = new TestModelConfiguration('chrome', 'http://example.com');

        return [
            'empty generated test output collection' => [
                'suiteManifest' => new SuiteManifest($compilerConfiguration, []),
                'expectedTestPaths' => [],
            ],
            'populated generated test output collection' => [
                'suiteManifest' => new SuiteManifest($compilerConfiguration, [
                    new TestManifest($testConfiguration, 'test1.yml', 'GeneratedTest1.php'),
                    new TestManifest($testConfiguration, 'test2.yml', 'GeneratedTest2.php'),
                    new TestManifest($testConfiguration, 'test3.yml', 'GeneratedTest3.php'),
                ]),
                'expectedTestPaths' => [
                    'build/GeneratedTest1.php',
                    'build/GeneratedTest2.php',
                    'build/GeneratedTest3.php',
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
        $compilerConfiguration = new Configuration('test.yml', 'build', SuiteManifestTest::class);
        $testConfiguration = new TestModelConfiguration('chrome', 'http://example.com');

        return [
            'empty generated test output collection' => [
                'suiteManifest' => new SuiteManifest($compilerConfiguration, []),
            ],
            'populated generated test output collection' => [
                'suiteManifest' => new SuiteManifest($compilerConfiguration, [
                    new TestManifest($testConfiguration, 'test1.yml', 'GeneratedTest1.php'),
                    new TestManifest($testConfiguration, 'test2.yml', 'GeneratedTest2.php'),
                    new TestManifest($testConfiguration, 'test3.yml', 'GeneratedTest3.php'),
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
                            'test.yml',
                            'GeneratedTest.php'
                        ),
                    ]
                ),
                'expectedValidationState' => suiteManifest::VALIDATION_STATE_VALID,
            ],
        ];
    }
}
