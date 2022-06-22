<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Tests\Unit\Model;

use Mockery;
use phpmock\mockery\PHPMockery;
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

    /**
     * @dataProvider isValidDataProvider
     */
    public function testValidate(
        ConfigurationInterface $configuration,
        int $expectedValidationState,
        ?callable $initializer = null
    ): void {
        if (is_callable($initializer)) {
            $initializer();
        }

        self::assertSame($expectedValidationState, $configuration->validate());

        Mockery::close();
    }

    /**
     * @return array<mixed>
     */
    public function isValidDataProvider(): array
    {
        $mockNamespace = 'webignition\BasilCompilerModels\Model';

        $isReadableMockArguments = [
            $mockNamespace,
            'is_readable',
        ];

        $isDirMockArguments = [
            $mockNamespace,
            'is_dir'
        ];

        $isWritableMockArguments = [
            $mockNamespace,
            'is_writable',
        ];

        return [
            'source empty' => [
                'configuration' => new Configuration('', '', ''),
                'expectedValidationState' => Configuration::VALIDATION_STATE_SOURCE_EMPTY,
            ],
            'source not absolute' => [
                'configuration' => new Configuration('relative/path/test.yml', '', ''),
                'expectedValidationState' => Configuration::VALIDATION_STATE_SOURCE_NOT_ABSOLUTE,
            ],
            'source not readable' => [
                'configuration' => new Configuration('/unreadable.yml', '', ''),
                'expectedValidationState' => Configuration::VALIDATION_STATE_SOURCE_NOT_READABLE,
                'initializer' => function () use ($isReadableMockArguments) {
                    PHPMockery::mock(...$isReadableMockArguments)
                        ->with('/unreadable.yml')
                        ->andReturnFalse()
                    ;
                },
            ],
            'target empty' => [
                'configuration' => new Configuration('/test.yml', '', ''),
                'expectedValidationState' => Configuration::VALIDATION_STATE_TARGET_EMPTY,
                'initializer' => function () use ($isReadableMockArguments) {
                    PHPMockery::mock(...$isReadableMockArguments)
                        ->with('/test.yml')
                        ->andReturnTrue()
                    ;
                },
            ],
            'target not absolute' => [
                'configuration' => new Configuration('/test.yml', 'relative/path', ''),
                'expectedValidationState' => Configuration::VALIDATION_STATE_TARGET_NOT_ABSOLUTE,
                'initializer' => function () use ($isReadableMockArguments) {
                    PHPMockery::mock(...$isReadableMockArguments)
                        ->with('/test.yml')
                        ->andReturnTrue()
                    ;
                },
            ],
            'target not a directory' => [
                'configuration' => new Configuration('/test.yml', '/target.yml', ''),
                'expectedValidationState' => Configuration::VALIDATION_STATE_TARGET_NOT_DIRECTORY,
                'initializer' => function () use ($isReadableMockArguments, $isDirMockArguments) {
                    PHPMockery::mock(...$isReadableMockArguments)
                        ->with('/test.yml')
                        ->andReturnTrue()
                    ;

                    PHPMockery::mock(...$isDirMockArguments)
                        ->with('/target.yml')
                        ->andReturnFalse()
                    ;
                },
            ],
            'target not a writable' => [
                'configuration' => new Configuration('/test.yml', '/target', ''),
                'expectedValidationState' => Configuration::VALIDATION_STATE_TARGET_NOT_WRITABLE,
                'initializer' => function () use (
                    $isReadableMockArguments,
                    $isDirMockArguments,
                    $isWritableMockArguments
                ) {
                    PHPMockery::mock(...$isReadableMockArguments)
                        ->with('/test.yml')
                        ->andReturnTrue()
                    ;

                    PHPMockery::mock(...$isDirMockArguments)
                        ->with('/target')
                        ->andReturnTrue()
                    ;

                    PHPMockery::mock(...$isWritableMockArguments)
                        ->with('/target')
                        ->andReturnFalse()
                    ;
                },
            ],
            'valid' => [
                'configuration' => new Configuration('/test.yml', '/target', ''),
                'expectedValidationState' => Configuration::VALIDATION_STATE_VALID,
                'initializer' => function () use (
                    $isReadableMockArguments,
                    $isDirMockArguments,
                    $isWritableMockArguments
                ) {
                    PHPMockery::mock(...$isReadableMockArguments)
                        ->with('/test.yml')
                        ->andReturnTrue()
                    ;

                    PHPMockery::mock(...$isDirMockArguments)
                        ->with('/target')
                        ->andReturnTrue()
                    ;

                    PHPMockery::mock(...$isWritableMockArguments)
                        ->with('/target')
                        ->andReturnTrue()
                    ;
                },
            ],
        ];
    }
}
