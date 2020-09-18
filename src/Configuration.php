<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels;

class Configuration implements ConfigurationInterface
{
    public const VALIDATION_STATE_VALID = 1;
    public const VALIDATION_STATE_SOURCE_NOT_READABLE = 2;
    public const VALIDATION_STATE_TARGET_NOT_DIRECTORY = 3;
    public const VALIDATION_STATE_TARGET_NOT_WRITABLE = 4;
    public const VALIDATION_STATE_SOURCE_NOT_ABSOLUTE = 5;
    public const VALIDATION_STATE_TARGET_NOT_ABSOLUTE = 6;
    public const VALIDATION_STATE_SOURCE_EMPTY = 7;
    public const VALIDATION_STATE_TARGET_EMPTY = 8;

    private string $source;
    private string $target;
    private string $baseClass;

    public function __construct(string $source, string $target, string $baseClass)
    {
        $this->source = $source;
        $this->target = $target;
        $this->baseClass = $baseClass;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    public function getBaseClass(): string
    {
        return $this->baseClass;
    }

    /**
     * @return array<string, string>
     */
    public function getData(): array
    {
        return [
            'source' => $this->source,
            'target' => $this->target,
            'base-class' => $this->baseClass,
        ];
    }

    public function validate(): int
    {
        if ('' === $this->source) {
            return self::VALIDATION_STATE_SOURCE_EMPTY;
        }

        $sourceFirstCharacter = $this->source[0] ?? '';
        if (DIRECTORY_SEPARATOR !== $sourceFirstCharacter) {
            return self::VALIDATION_STATE_SOURCE_NOT_ABSOLUTE;
        }

        if (!is_readable($this->source)) {
            return self::VALIDATION_STATE_SOURCE_NOT_READABLE;
        }

        if ('' === $this->target) {
            return self::VALIDATION_STATE_TARGET_EMPTY;
        }

        $targetFirstCharacter = $this->target[0] ?? '';
        if (DIRECTORY_SEPARATOR !== $targetFirstCharacter) {
            return self::VALIDATION_STATE_TARGET_NOT_ABSOLUTE;
        }

        if (!is_dir($this->target)) {
            return self::VALIDATION_STATE_TARGET_NOT_DIRECTORY;
        }

        if (!is_writable($this->target)) {
            return self::VALIDATION_STATE_TARGET_NOT_WRITABLE;
        }

        return self::VALIDATION_STATE_VALID;
    }

    /**
     * @param array<mixed> $data
     *
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new Configuration(
            $data['source'] ?? '',
            $data['target'] ?? '',
            $data['base-class'] ?? ''
        );
    }
}
