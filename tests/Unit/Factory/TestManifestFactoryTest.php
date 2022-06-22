<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Tests\Unit\Factory;

use PHPUnit\Framework\TestCase;
use webignition\BasilCompilerModels\Exception\InvalidTestManifestException;
use webignition\BasilCompilerModels\Factory\TestManifestFactory;
use webignition\BasilCompilerModels\Model\TestManifest;

class TestManifestFactoryTest extends TestCase
{
    /**
     * @dataProvider invalidConfigBrowserDataProvider
     * @dataProvider invalidConfigUrlDataProvider
     * @dataProvider invalidSourceDataProvider
     *
     * @param array<mixed> $data
     */
    public function testCreateThrowsInvalidTestManifestException(
        array $data,
        InvalidTestManifestException $expected
    ): void {
        self::expectException($expected::class);
        self::expectExceptionCode($expected->getCode());

        (new TestManifestFactory())->create($data);
    }

    /**
     * @return array<mixed>
     */
    public function invalidConfigBrowserDataProvider(): array
    {
        $configData = [
            'url' => md5((string) rand())
        ];

        $data = [
            'source' => md5((string) rand()),
            'target' => md5((string) rand()),
            'step_names' => [md5((string) rand())],
        ];

        return [
            'config.browser missing' => [
                'data' => array_merge(
                    [
                        'config' => $configData,
                    ],
                    $data
                ),
                'expected' => InvalidTestManifestException::createForEmptyBrowser(),
            ],
            'config.browser empty' => [
                'data' => array_merge(
                    [
                        'config' => array_merge(['browser' => ''], $configData),
                    ],
                    $data
                ),
                'expected' => InvalidTestManifestException::createForEmptyBrowser(),
            ],
            'config.browser whitespace-only' => [
                'data' => array_merge(
                    [
                        'config' => array_merge(['browser' => '  '], $configData),
                    ],
                    $data
                ),
                'expected' => InvalidTestManifestException::createForEmptyBrowser(),
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    public function invalidConfigUrlDataProvider(): array
    {
        $configData = [
            'browser' => md5((string) rand())
        ];

        $data = [
            'source' => md5((string) rand()),
            'target' => md5((string) rand()),
            'step_names' => [md5((string) rand())],
        ];

        return [
            'config.url missing' => [
                'data' => array_merge(
                    [
                        'config' => $configData,
                    ],
                    $data
                ),
                'expected' => InvalidTestManifestException::createForEmptyUrl(),
            ],
            'config.url empty' => [
                'data' => array_merge(
                    [
                        'config' => array_merge(['url' => ''], $configData),
                    ],
                    $data
                ),
                'expected' => InvalidTestManifestException::createForEmptyUrl(),
            ],
            'config.url whitespace-only' => [
                'data' => array_merge(
                    [
                        'config' => array_merge(['url' => '  '], $configData),
                    ],
                    $data
                ),
                'expected' => InvalidTestManifestException::createForEmptyUrl(),
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    public function invalidSourceDataProvider(): array
    {
        $data = [
            'config' => [
                'browser' => md5((string) rand()),
                'url' => md5((string) rand()),
            ],
            'target' => md5((string) rand()),
            'step_names' => [md5((string) rand())],
        ];

        return [
            'source missing' => [
                'data' => $data,
                'expected' => InvalidTestManifestException::createForEmptySource(),
            ],
            'source empty' => [
                'data' => array_merge(['source' => ''], $data),
                'expected' => InvalidTestManifestException::createForEmptySource(),
            ],
            'source whitespace-only' => [
                'data' => array_merge(['source' => '  '], $data),
                'expected' => InvalidTestManifestException::createForEmptySource(),
            ],
        ];
    }

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
