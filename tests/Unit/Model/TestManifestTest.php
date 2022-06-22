<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use webignition\BasilCompilerModels\Model\TestManifest;

class TestManifestTest extends TestCase
{
    /**
     * @dataProvider valueDataProvider
     */
    public function testGetSource(string $value): void
    {
        self::assertSame($value, (new TestManifest('', '', $value, '', []))->getSource());
    }

    /**
     * @dataProvider valueDataProvider
     */
    public function testGetTarget(string $value): void
    {
        self::assertSame($value, (new TestManifest('', '', '', $value, []))->getTarget());
    }

    /**
     * @dataProvider valueDataProvider
     */
    public function testGetBrowser(string $value): void
    {
        self::assertSame($value, (new TestManifest($value, '', '', '', []))->getBrowser());
    }

    /**
     * @dataProvider valueDataProvider
     */
    public function testGetUrl(string $value): void
    {
        self::assertSame($value, (new TestManifest('', $value, '', '', []))->getUrl());
    }

    /**
     * @return array<mixed>
     */
    public function valueDataProvider(): array
    {
        return [
            'empty' => [
                'value' => '',
            ],
            'non-empty' => [
                'value' => md5((string) rand()),
            ],
        ];
    }

    public function testGetStepNames(): void
    {
        $stepNames = ['step one', 'step two', 'step three'];

        self::assertSame($stepNames, (new TestManifest('', '', '', '', $stepNames))->getStepNames());
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
            'empty' => [
                'manifest' => new TestManifest('', '', '', '', []),
                'expected' => [
                    'config' => [
                        'browser' => '',
                        'url' => '',
                    ],
                    'source' => '',
                    'target' => '',
                    'step_names' => [],
                ],
            ],
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
        $validStepNames = ['step one'];

        return [
            'browser invalid' => [
                'testManifest' => new TestManifest(
                    '',
                    md5((string) rand()),
                    md5((string) rand()),
                    md5((string) rand()),
                    $validStepNames
                ),
                'expectedValidationState' => TestManifest::VALIDATION_STATE_BROWSER_EMPTY,
            ],
            'url invalid' => [
                'testManifest' => new TestManifest(
                    md5((string) rand()),
                    '',
                    md5((string) rand()),
                    md5((string) rand()),
                    $validStepNames
                ),
                'expectedValidationState' => TestManifest::VALIDATION_STATE_URL_EMPTY,
            ],
            'source empty' => [
                'testManifest' => new TestManifest(
                    md5((string) rand()),
                    md5((string) rand()),
                    '',
                    md5((string) rand()),
                    $validStepNames
                ),
                'expectedValidationState' => TestManifest::VALIDATION_STATE_SOURCE_EMPTY,
            ],
            'target empty' => [
                'testManifest' => new TestManifest(
                    md5((string) rand()),
                    md5((string) rand()),
                    md5((string) rand()),
                    '',
                    $validStepNames
                ),
                'expectedValidationState' => TestManifest::VALIDATION_STATE_TARGET_EMPTY,
            ],
            'valid' => [
                'testManifest' => new TestManifest(
                    md5((string) rand()),
                    md5((string) rand()),
                    md5((string) rand()),
                    md5((string) rand()),
                    $validStepNames
                ),
                'expectedValidationState' => TestManifest::VALIDATION_STATE_VALID,
            ],
        ];
    }
}
