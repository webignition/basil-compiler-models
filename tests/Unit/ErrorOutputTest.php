<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Tests\Unit;

use PHPUnit\Framework\TestCase;
use webignition\BasilCompilerModels\ErrorOutput;
use webignition\BasilCompilerModels\ErrorOutputInterface;

class ErrorOutputTest extends TestCase
{
    public function testGetCode(): void
    {
        $code = rand();

        self::assertSame($code, (new ErrorOutput('message', $code))->getCode());
    }

    /**
     * @dataProvider toArrayFromArrayDataProvider
     */
    public function testToArrayFromArray(ErrorOutputInterface $output): void
    {
        self::assertEquals($output, ErrorOutput::fromArray($output->toArray()));
    }

    /**
     * @return array<mixed>
     */
    public function toArrayFromArrayDataProvider(): array
    {
        return [
            'without context' => [
                'output' => new ErrorOutput(
                    'error-message-01',
                    1
                ),
            ],
            'with context' => [
                'output' => new ErrorOutput(
                    'error-message-01',
                    1,
                    [
                        'context-key-01' => 'context-value-01',
                        'context-key-02' => 'context-value-02',
                    ]
                ),
            ],
        ];
    }
}
