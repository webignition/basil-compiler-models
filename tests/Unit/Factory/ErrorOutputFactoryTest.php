<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Tests\Unit\Factory;

use PHPUnit\Framework\TestCase;
use webignition\BasilCompilerModels\Factory\ErrorOutputFactory;
use webignition\BasilCompilerModels\Model\ErrorOutput;
use webignition\BasilCompilerModels\Model\ErrorOutputInterface;

class ErrorOutputFactoryTest extends TestCase
{
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
            'empty message' => [
                'data' => [
                    'message' => '',
                    'code' => 2,
                ],
                'output' => new ErrorOutput('', 2),
            ],
        ];
    }
}
