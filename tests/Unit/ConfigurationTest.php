<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Tests\Unit;

use Mockery;
use phpmock\mockery\PHPMockery;
use PHPUnit\Framework\TestCase;
use webignition\BasilCompilerModels\Configuration;
use webignition\BasilCompilerModels\ConfigurationInterface;

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

    public function testGetData(): void
    {
        self::assertSame(
            [
                'source' => self::SOURCE,
                'target' => self::TARGET,
                'base-class' => self::BASE_CLASS,
            ],
            $this->configuration->getData()
        );
    }

    /**
     * @dataProvider fromArrayDataProvider
     *
     * @param array<mixed> $data
     * @param Configuration $expectedConfiguration
     */
    public function testFromArray(array $data, Configuration $expectedConfiguration): void
    {
        self::assertEquals($expectedConfiguration, Configuration::fromArray($data));
    }

    /**
     * @return array[]
     */
    public function fromArrayDataProvider(): array
    {
        return [
            'empty' => [
                'data' => [],
                'expectedConfiguration' => new Configuration('', '', '')
            ],
            'source only' => [
                'data' => [
                    'source' => self::SOURCE,
                ],
                'expectedConfiguration' => new Configuration(self::SOURCE, '', '')
            ],
            'target only' => [
                'data' => [
                    'target' => self::TARGET,
                ],
                'expectedConfiguration' => new Configuration('', self::TARGET, '')
            ],
            'base-class only' => [
                'data' => [
                    'base-class' => self::BASE_CLASS,
                ],
                'expectedConfiguration' => new Configuration('', '', self::BASE_CLASS)
            ],
            'populated' => [
                'data' => [
                    'source' => self::SOURCE,
                    'target' => self::TARGET,
                    'base-class' => self::BASE_CLASS,
                ],
                'expectedConfiguration' => new Configuration(self::SOURCE, self::TARGET, self::BASE_CLASS)
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
     * @return array[]
     */
    public function isValidDataProvider(): array
    {
        $mockNamespace = 'webignition\BasilCompilerModels';

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
                        ->andReturnFalse();
                },
            ],
            'target empty' => [
                'configuration' => new Configuration('/test.yml', '', ''),
                'expectedValidationState' => Configuration::VALIDATION_STATE_TARGET_EMPTY,
                'initializer' => function () use ($isReadableMockArguments) {
                    PHPMockery::mock(...$isReadableMockArguments)
                        ->with('/test.yml')
                        ->andReturnTrue();
                },
            ],
            'target not absolute' => [
                'configuration' => new Configuration('/test.yml', 'relative/path', ''),
                'expectedValidationState' => Configuration::VALIDATION_STATE_TARGET_NOT_ABSOLUTE,
                'initializer' => function () use ($isReadableMockArguments) {
                    PHPMockery::mock(...$isReadableMockArguments)
                        ->with('/test.yml')
                        ->andReturnTrue();
                },
            ],
            'target not a directory' => [
                'configuration' => new Configuration('/test.yml', '/target.yml', ''),
                'expectedValidationState' => Configuration::VALIDATION_STATE_TARGET_NOT_DIRECTORY,
                'initializer' => function () use ($isReadableMockArguments, $isDirMockArguments) {
                    PHPMockery::mock(...$isReadableMockArguments)
                        ->with('/test.yml')
                        ->andReturnTrue();

                    PHPMockery::mock(...$isDirMockArguments)
                        ->with('/target.yml')
                        ->andReturnFalse();
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
                        ->andReturnTrue();

                    PHPMockery::mock(...$isDirMockArguments)
                        ->with('/target')
                        ->andReturnTrue();

                    PHPMockery::mock(...$isWritableMockArguments)
                        ->with('/target')
                        ->andReturnFalse();
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
                        ->andReturnTrue();

                    PHPMockery::mock(...$isDirMockArguments)
                        ->with('/target')
                        ->andReturnTrue();

                    PHPMockery::mock(...$isWritableMockArguments)
                        ->with('/target')
                        ->andReturnTrue();
                },
            ],
        ];
    }
}
