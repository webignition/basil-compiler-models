<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels;

class ErrorOutput extends AbstractOutput implements ErrorOutputInterface
{
    public const CODE_UNKNOWN = 99;

    /**
     * @param array<mixed> $context
     */
    public function __construct(
        ConfigurationInterface $configuration,
        private readonly string $message,
        private readonly int $code,
        private readonly array $context = []
    ) {
        parent::__construct($configuration);
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getData(): array
    {
        $errorData = [
            'message' => $this->message,
            'code' => $this->getCode(),
        ];

        if ([] !== $this->context) {
            $errorData['context'] = $this->context;
        }

        $serializedData = parent::getData();
        $serializedData['error'] = $errorData;

        return $serializedData;
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): ErrorOutput
    {
        $configData = $data['config'] ?? [];
        $configData = is_array($configData) ? $configData : [];

        $errorData = $data['error'] ?? [];
        $errorData = is_array($errorData) ? $errorData : [];

        $contextData = $errorData['context'] ?? [];
        $contextData = is_array($contextData) ? $contextData : [];

        return new ErrorOutput(
            Configuration::fromArray($configData),
            $errorData['message'] ?? '',
            (int) ($errorData['code'] ?? self::CODE_UNKNOWN),
            $contextData
        );
    }
}
