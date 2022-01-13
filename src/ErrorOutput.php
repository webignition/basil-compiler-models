<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels;

class ErrorOutput extends AbstractOutput implements ErrorOutputInterface
{
    public const CODE_UNKNOWN = 99;

    private int $code;
    private string $message;

    /**
     * @var array<mixed>
     */
    private array $context;

    /**
     * @param array<mixed> $context
     */
    public function __construct(ConfigurationInterface $configuration, string $message, int $code, array $context = [])
    {
        parent::__construct($configuration);

        $this->code = $code;
        $this->message = $message;
        $this->context = $context;
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
