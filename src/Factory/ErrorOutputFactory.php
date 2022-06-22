<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Factory;

use webignition\BasilCompilerModels\Exception\InvalidErrorOutputException;
use webignition\BasilCompilerModels\Model\ErrorOutput;
use webignition\BasilCompilerModels\Model\ErrorOutputInterface;

class ErrorOutputFactory
{
    public const CODE_UNKNOWN = 99;

    /**
     * @param array<mixed> $data
     *
     * @throws InvalidErrorOutputException
     */
    public function create(array $data): ErrorOutputInterface
    {
        $message = $data['message'] ?? '';
        $message = is_string($message) ? trim($message) : '';
        if ('' === $message) {
            throw InvalidErrorOutputException::createForEmptyMessage();
        }

        $code = $data['code'] ?? self::CODE_UNKNOWN;
        $code = is_int($code) ? $code : self::CODE_UNKNOWN;

        $context = $data['context'] ?? [];
        $context = is_array($context) ? $context : [];

        return new ErrorOutput($message, $code, $context);
    }
}
