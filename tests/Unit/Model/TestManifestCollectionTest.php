<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use webignition\BasilCompilerModels\Model\TestManifest;
use webignition\BasilCompilerModels\Model\TestManifestCollection;

class TestManifestCollectionTest extends TestCase
{
    /**
     * @dataProvider createDataProvider
     *
     * @param array<mixed> $testManifests
     */
    public function testCreate(array $testManifests, TestManifestCollection $expected): void
    {
        self::assertEquals(
            $expected,
            new TestManifestCollection($testManifests)
        );
    }

    /**
     * @return array<mixed>
     */
    public function createDataProvider(): array
    {
        $testManifests = [
            new TestManifest('browser1', 'url1', 'source1', 'target1', ['step 1']),
            new TestManifest('browser2', 'url2', 'source2', 'target2', ['step 2', 'step 3']),
            new TestManifest('browser3', 'url3', 'source3', 'target3', ['step 4']),
        ];

        return [
            'empty' => [
                'testManifests' => [],
                'expected' => new TestManifestCollection([])
            ],
            'non-empty, no test manifests' => [
                'testManifests' => [
                    true,
                    false,
                    1,
                    'string',
                    M_PI,
                ],
                'expected' => new TestManifestCollection([])
            ],
            'non-empty' => [
                'testManifests' => $testManifests,
                'expected' => new TestManifestCollection($testManifests)
            ],
        ];
    }

    /**
     * @param array<mixed> $expected
     *
     * @dataProvider toArrayFromArrayDataProvider
     */
    public function testToArray(TestManifestCollection $collection, array $expected): void
    {
        self::assertEquals($expected, $collection->toArray());
    }

    /**
     * @return array<mixed>
     */
    public function toArrayFromArrayDataProvider(): array
    {
        $manifest1 = new TestManifest(
            md5((string) rand()),
            md5((string) rand()),
            md5((string) rand()),
            md5((string) rand()),
            [md5((string) rand())]
        );

        $manifest2 = new TestManifest(
            md5((string) rand()),
            md5((string) rand()),
            md5((string) rand()),
            md5((string) rand()),
            [md5((string) rand()), md5((string) rand())]
        );

        return [
            'empty' => [
                'collection' => new TestManifestCollection([]),
                'expected' => [],
            ],
            'non-empty' => [
                'collection' => new TestManifestCollection([$manifest1, $manifest2]),
                'expected' => [
                    [
                        'config' => [
                            'browser' => $manifest1->getBrowser(),
                            'url' => $manifest1->getUrl(),
                        ],
                        'source' => $manifest1->getSource(),
                        'target' => $manifest1->getTarget(),
                        'step_names' => $manifest1->getStepNames(),
                    ],
                    [
                        'config' => [
                            'browser' => $manifest2->getBrowser(),
                            'url' => $manifest2->getUrl(),
                        ],
                        'source' => $manifest2->getSource(),
                        'target' => $manifest2->getTarget(),
                        'step_names' => $manifest2->getStepNames(),
                    ],
                ],
            ],
        ];
    }
}
