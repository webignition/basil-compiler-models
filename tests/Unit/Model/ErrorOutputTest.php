<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use webignition\BasilCompilerModels\Model\ErrorOutput;
use webignition\BasilCompilerModels\Model\ErrorOutputInterface;

class ErrorOutputTest extends TestCase
{
    public function testGetCode(): void
    {
        $code = rand();

        self::assertSame($code, (new ErrorOutput('message', $code))->getCode());
    }

    /**
     * @param array<mixed> $expected
     *
     * @dataProvider toArrayDataProvider
     */
    public function testToArray(ErrorOutputInterface $output, array $expected): void
    {
        self::assertEquals($expected, $output->toArray());
    }

    /**
     * @return array<mixed>
     */
    public function toArrayDataProvider(): array
    {
        return [
            'without context' => [
                'output' => new ErrorOutput(
                    'error-message-01',
                    1
                ),
                'expected' => [
                    'message' => 'error-message-01',
                    'code' => 1
                ],
            ],
            'with context' => [
                'output' => new ErrorOutput(
                    'error-message-02',
                    2,
                    [
                        'context-key-01' => 'context-value-01',
                        'context-key-02' => 'context-value-02',
                    ]
                ),
                'expected' => [
                    'message' => 'error-message-02',
                    'code' => 2,
                    'context' => [
                        'context-key-01' => 'context-value-01',
                        'context-key-02' => 'context-value-02',
                    ],
                ],
            ],
        ];
    }
}
