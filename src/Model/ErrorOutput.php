<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Model;

class ErrorOutput implements ErrorOutputInterface
{
    /**
     * @param non-empty-string $message
     * @param array<mixed>     $context
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

    /**
     * @return array{message: non-empty-string, code: int, context?: array<mixed>}
     */
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
}
