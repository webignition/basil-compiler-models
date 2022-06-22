<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Tests\Unit\Factory;

use PHPUnit\Framework\TestCase;
use webignition\BasilCompilerModels\Factory\TestManifestFactory;
use webignition\BasilCompilerModels\Model\TestManifest;

class TestManifestFactoryTest extends TestCase
{
    /**
     * @param array<mixed> $data
     *
     * @dataProvider createDataProvider
     */
    public function testCreate(array $data, TestManifest $expected): void
    {
        self::assertEquals($expected, (new TestManifestFactory())->create($data));
    }

    /**
     * @return array<mixed>
     */
    public function createDataProvider(): array
    {
        $browser = md5((string) rand());
        $url = md5((string) rand());
        $source = md5((string) rand());
        $target = md5((string) rand());
        $stepName1 = md5((string) rand());
        $stepName2 = md5((string) rand());
        $stepName3 = md5((string) rand());

        return [
            'no data' => [
                'data' => [],
                'expected' => new TestManifest('', '', '', '', []),
            ],
            'empty data' => [
                'data' => [
                    'config' => [],
                    'source' => '',
                    'target' => '',
                    'step_names' => [],
                ],
                'expected' => new TestManifest('', '', '', '', []),
            ],
            'config data not an array' => [
                'data' => [
                    'config' => true,
                    'source' => $source,
                    'target' => $target,
                    'step_names' => [$stepName1],
                ],
                'expected' => new TestManifest('', '', $source, $target, [$stepName1]),
            ],
            'source is not a string' => [
                'data' => [
                    'config' => [
                        'browser' => $browser,
                        'url' => $url
                    ],
                    'source' => 100,
                    'target' => $target,
                    'step_names' => [$stepName1],
                ],
                'expected' => new TestManifest($browser, $url, '', $target, [$stepName1]),
            ],
            'target is not a string' => [
                'data' => [
                    'config' => [
                        'browser' => $browser,
                        'url' => $url
                    ],
                    'source' => $source,
                    'target' => M_PI,
                    'step_names' => [$stepName1],
                ],
                'expected' => new TestManifest($browser, $url, $source, '', [$stepName1]),
            ],
            'step names are all not strings' => [
                'data' => [
                    'config' => [
                        'browser' => $browser,
                        'url' => $url
                    ],
                    'source' => $source,
                    'target' => $target,
                    'step_names' => [true, false, M_PI],
                ],
                'expected' => new TestManifest($browser, $url, $source, $target, []),
            ],
            'step names are all empty strings' => [
                'data' => [
                    'config' => [
                        'browser' => $browser,
                        'url' => $url
                    ],
                    'source' => $source,
                    'target' => $target,
                    'step_names' => ['', '', ''],
                ],
                'expected' => new TestManifest($browser, $url, $source, $target, []),
            ],
            'all values present and valid' => [
                'data' => [
                    'config' => [
                        'browser' => $browser,
                        'url' => $url
                    ],
                    'source' => $source,
                    'target' => $target,
                    'step_names' => [$stepName1, $stepName2, $stepName3],
                ],
                'expected' => new TestManifest($browser, $url, $source, $target, [$stepName1, $stepName2, $stepName3]),
            ],
        ];
    }
}
