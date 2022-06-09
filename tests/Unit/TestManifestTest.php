<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Tests\Unit;

use PHPUnit\Framework\TestCase;
use webignition\BasilCompilerModels\TestManifest;

class TestManifestTest extends TestCase
{
    private const BROWSER = 'chrome';
    private const URL = 'https://example.com';
    private const SOURCE = 'test.yml';
    private const TARGET = 'GeneratedTest.php';
    private const STEP_NAMES = [
        'step one',
        'step two',
        'step three',
    ];

    private TestManifest $manifest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->manifest = new TestManifest(
            self::BROWSER,
            self::URL,
            self::SOURCE,
            self::TARGET,
            self::STEP_NAMES
        );
    }

    public function testGetSource(): void
    {
        self::assertSame(self::SOURCE, $this->manifest->getSource());
    }

    public function testGetTarget(): void
    {
        self::assertSame(self::TARGET, $this->manifest->getTarget());
    }

    public function testGetBrowser(): void
    {
        self::assertSame(self::BROWSER, $this->manifest->getBrowser());
    }

    public function testGetUrl(): void
    {
        self::assertSame(self::URL, $this->manifest->getUrl());
    }

    public function testGetStepNames(): void
    {
        self::assertSame(self::STEP_NAMES, $this->manifest->getStepNames());
    }

    public function testGetData(): void
    {
        self::assertSame(
            [
                'config' => [
                    'browser' => self::BROWSER,
                    'url' => self::URL,
                ],
                'source' => self::SOURCE,
                'target' => self::TARGET,
                'step_names' => self::STEP_NAMES,
            ],
            $this->manifest->getData()
        );
    }

    public function testFromArray(): void
    {
        self::assertEquals(
            new TestManifest(
                self::BROWSER,
                self::URL,
                self::SOURCE,
                self::TARGET,
                self::STEP_NAMES
            ),
            TestManifest::fromArray([
                'config' => [
                    'browser' => self::BROWSER,
                    'url' => self::URL,
                ],
                'source' => self::SOURCE,
                'target' => self::TARGET,
                'step_names' => self::STEP_NAMES,
            ])
        );
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
        return [
            'browser invalid' => [
                'testManifest' => new TestManifest(
                    '',
                    self::URL,
                    self::SOURCE,
                    self::TARGET,
                    self::STEP_NAMES
                ),
                'expectedValidationState' => TestManifest::VALIDATION_STATE_BROWSER_EMPTY,
            ],
            'url invalid' => [
                'testManifest' => new TestManifest(
                    self::BROWSER,
                    '',
                    self::SOURCE,
                    self::TARGET,
                    self::STEP_NAMES
                ),
                'expectedValidationState' => TestManifest::VALIDATION_STATE_URL_EMPTY,
            ],
            'source empty' => [
                'testManifest' => new TestManifest(
                    self::BROWSER,
                    self::URL,
                    '',
                    self::TARGET,
                    self::STEP_NAMES
                ),
                'expectedValidationState' => TestManifest::VALIDATION_STATE_SOURCE_EMPTY,
            ],
            'target empty' => [
                'testManifest' => new TestManifest(
                    self::BROWSER,
                    self::URL,
                    self::SOURCE,
                    '',
                    self::STEP_NAMES
                ),
                'expectedValidationState' => TestManifest::VALIDATION_STATE_TARGET_EMPTY,
            ],
            'valid' => [
                'testManifest' => new TestManifest(
                    self::BROWSER,
                    self::URL,
                    self::URL,
                    self::TARGET,
                    self::STEP_NAMES
                ),
                'expectedValidationState' => TestManifest::VALIDATION_STATE_VALID,
            ],
        ];
    }
}
