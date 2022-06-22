<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use webignition\BasilCompilerModels\Model\TestManifest;

class TestManifestTest extends TestCase
{
    public function testGetSource(): void
    {
        $source = md5((string) rand());

        $manifest = new TestManifest('browser', 'url', $source, 'target', []);

        self::assertSame($source, $manifest->getSource());
    }

    public function testGetTarget(): void
    {
        $target = md5((string) rand());

        $manifest = new TestManifest('browser', 'url', 'source', $target, []);

        self::assertSame($target, $manifest->getTarget());
    }

    public function testGetBrowser(): void
    {
        $browser = md5((string) rand());

        $manifest = new TestManifest($browser, 'url', 'source', 'target', []);

        self::assertSame($browser, $manifest->getBrowser());
    }

    public function testGetUrl(): void
    {
        $url = md5((string) rand());

        $manifest = new TestManifest('browser', $url, 'source', 'target', []);

        self::assertSame($url, $manifest->getUrl());
    }

    public function testGetStepNames(): void
    {
        $stepNames = ['step one', 'step two', 'step three'];

        $manifest = new TestManifest('browser', 'url', 'source', 'target', $stepNames);

        self::assertSame($stepNames, $manifest->getStepNames());
    }

    /**
     * @param array<mixed> $expected
     *
     * @dataProvider toArrayFromArrayDataProvider
     */
    public function testToArray(TestManifest $manifest, array $expected): void
    {
        self::assertEquals($expected, $manifest->toArray());
    }

    /**
     * @return array<mixed>
     */
    public function toArrayFromArrayDataProvider(): array
    {
        $nonEmptyManifest = new TestManifest(
            md5((string) rand()),
            md5((string) rand()),
            md5((string) rand()),
            md5((string) rand()),
            []
        );

        return [
            'non-empty' => [
                'manifest' => $nonEmptyManifest,
                'expected' => [
                    'config' => [
                        'browser' => $nonEmptyManifest->getBrowser(),
                        'url' => $nonEmptyManifest->getUrl(),
                    ],
                    'source' => $nonEmptyManifest->getSource(),
                    'target' => $nonEmptyManifest->getTarget(),
                    'step_names' => $nonEmptyManifest->getStepNames(),
                ]
            ],
        ];
    }
}
