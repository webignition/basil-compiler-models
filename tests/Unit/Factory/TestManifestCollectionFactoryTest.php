<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Tests\Unit\Factory;

use PHPUnit\Framework\TestCase;
use webignition\BasilCompilerModels\Factory\TestManifestCollectionFactory;
use webignition\BasilCompilerModels\Factory\TestManifestFactory;
use webignition\BasilCompilerModels\Model\TestManifest;
use webignition\BasilCompilerModels\Model\TestManifestCollection;

class TestManifestCollectionFactoryTest extends TestCase
{
    /**
     * @param array<mixed> $data
     *
     * @dataProvider createDataProvider
     */
    public function testCreate(array $data, TestManifestCollection $expected): void
    {
        $factory = new TestManifestCollectionFactory(
            new TestManifestFactory(),
        );

        self::assertEquals($expected, $factory->create($data));
    }

    /**
     * @return array<mixed>
     */
    public function createDataProvider(): array
    {
        return [
            'no data' => [
                'data' => [],
                'collection' => new TestManifestCollection([]),
            ],
            'only invalid data sets' => [
                'data' => [
                    [
                        'config' => [
                            'browser' => 'chrome',
                            'url' => 'http://example.com/1',
                        ],
                        'source' => '',
                        'target' => 'target1',
                        'step_names' => ['1', '2'],
                    ],
                    [
                        'config' => [
                            'browser' => 'firefox',
                            'url' => 'http://example.com/2',
                        ],
                        'source' => 'source2',
                        'target' => '',
                        'step_names' => ['3', '4'],
                    ],
                ],
                'collection' => new TestManifestCollection([]),
            ],
            'valid data sets' => [
                'data' => [
                    [
                        'config' => [
                            'browser' => 'chrome',
                            'url' => 'http://example.com/1',
                        ],
                        'source' => 'source1',
                        'target' => 'target1',
                        'step_names' => ['1', '2'],
                    ],
                    [
                        'config' => [
                            'browser' => 'firefox',
                            'url' => 'http://example.com/2',
                        ],
                        'source' => 'source2',
                        'target' => 'target2',
                        'step_names' => ['3', '4'],
                    ],
                ],
                'collection' => new TestManifestCollection([
                    new TestManifest('chrome', 'http://example.com/1', 'source1', 'target1', ['1', '2']),
                    new TestManifest('firefox', 'http://example.com/2', 'source2', 'target2', ['3', '4']),
                ]),
            ],
        ];
    }
}
