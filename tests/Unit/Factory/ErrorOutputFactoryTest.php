<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Tests\Unit\Factory;

use PHPUnit\Framework\TestCase;
use webignition\BasilCompilerModels\Exception\InvalidErrorOutputException;
use webignition\BasilCompilerModels\Factory\ErrorOutputFactory;
use webignition\BasilCompilerModels\Model\ErrorOutput;
use webignition\BasilCompilerModels\Model\ErrorOutputInterface;

class ErrorOutputFactoryTest extends TestCase
{
    public function testCreateWithEmptyMessage(): void
    {
        self::expectException(InvalidErrorOutputException::class);
        self::expectExceptionCode(InvalidErrorOutputException::CODE_MESSAGE_EMPTY);

        (new ErrorOutputFactory())->create([]);
    }

    /**
     * @param array<mixed> $data
     *
     * @dataProvider createDataProvider
     */
    public function testCreate(array $data, ErrorOutputInterface $expected): void
    {
        self::assertEquals($expected, (new ErrorOutputFactory())->create($data));
    }

    /**
     * @return array<mixed>
     */
    public function createDataProvider(): array
    {
        return [
            'non-empty' => [
                'data' => [
                    'message' => 'error message',
                    'code' => 1,
                ],
                'output' => new ErrorOutput('error message', 1),
            ],
        ];
    }
}
