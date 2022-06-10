<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels;

class ErrorOutput implements ErrorOutputInterface
{
    public const CODE_UNKNOWN = 99;

    /**
     * @param array<mixed> $context
     */
    public function __construct(
        private readonly string $message,
        private readonly int $code,
        private readonly array $context = []
    ) {
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function toArray(): array
    {
        $data = [
            'message' => $this->message,
            'code' => $this->getCode(),
        ];

        if ([] !== $this->context) {
            $data['context'] = $this->context;
        }

        return $data;
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): ErrorOutput
    {
        $message = $data['message'] ?? '';
        $message = is_string($message) ? $message : '';

        $code = $data['code'] ?? self::CODE_UNKNOWN;
        $code = is_int($code) ? $code : self::CODE_UNKNOWN;

        $context = $data['context'] ?? [];
        $context = is_array($context) ? $context : [];

        return new ErrorOutput($message, $code, $context);
    }
}
