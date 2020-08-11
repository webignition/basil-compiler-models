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
     * @param ConfigurationInterface $configuration
     * @param string $message
     * @param int $code
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
     *
     * @return ErrorOutput
     */
    public static function fromArray(array $data): ErrorOutput
    {
        $configData = $data['config'] ?? [];
        $errorData = $data['error'] ?? [];
        $contextData = $errorData['context'] ?? [];

        return new ErrorOutput(
            Configuration::fromArray($configData),
            $errorData['message'] ?? '',
            (int) ($errorData['code'] ?? self::CODE_UNKNOWN),
            $contextData
        );
    }
}
