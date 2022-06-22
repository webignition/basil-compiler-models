<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use webignition\BasilCompilerModels\Model\Configuration;
use webignition\BasilCompilerModels\Model\ConfigurationInterface;

class ConfigurationTest extends TestCase
{
    private const SOURCE = 'test.yml';
    private const TARGET = 'build';
    private const BASE_CLASS = 'BaseClass';

    private ConfigurationInterface $configuration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->configuration = new Configuration(self::SOURCE, self::TARGET, self::BASE_CLASS);
    }

    public function testGetSource(): void
    {
        self::assertSame(self::SOURCE, $this->configuration->getSource());
    }

    public function testGetTarget(): void
    {
        self::assertSame(self::TARGET, $this->configuration->getTarget());
    }

    public function testGetBaseClass(): void
    {
        self::assertSame(self::BASE_CLASS, $this->configuration->getBaseClass());
    }

    /**
     * @param array<mixed> $expected
     *
     * @dataProvider toArrayDataProvider
     */
    public function testToArray(Configuration $configuration, array $expected): void
    {
        self::assertEquals($expected, $configuration->toArray());
    }

    /**
     * @return array<mixed>
     */
    public function toArrayDataProvider(): array
    {
        return [
            'empty' => [
                'configuration' => new Configuration('', '', ''),
                'expected' => [
                    'source' => '',
                    'target' => '',
                    'base-class' => '',
                ],
            ],
            'source only' => [
                'configuration' => new Configuration(self::SOURCE, '', ''),
                'expected' => [
                    'source' => self::SOURCE,
                    'target' => '',
                    'base-class' => '',
                ],
            ],
            'target only' => [
                'configuration' => new Configuration('', self::TARGET, ''),
                'expected' => [
                    'source' => '',
                    'target' => self::TARGET,
                    'base-class' => '',
                ],
            ],
            'base-class only' => [
                'configuration' => new Configuration('', '', self::BASE_CLASS),
                'expected' => [
                    'source' => '',
                    'target' => '',
                    'base-class' => self::BASE_CLASS,
                ],
            ],
            'populated' => [
                'configuration' => new Configuration(self::SOURCE, self::TARGET, self::BASE_CLASS),
                'expected' => [
                    'source' => self::SOURCE,
                    'target' => self::TARGET,
                    'base-class' => self::BASE_CLASS,
                ],
            ],
        ];
    }
}
