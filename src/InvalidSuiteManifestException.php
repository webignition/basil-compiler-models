<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels;

class InvalidSuiteManifestException extends \Exception
{
    public function __construct(
        private readonly SuiteManifest $suiteManifest,
        private readonly int $validationState
    ) {
        parent::__construct(sprintf(
            'Invalid suite manifest. Validation state %s',
            $validationState
        ));
    }

    public function getSuiteManifest(): SuiteManifest
    {
        return $this->suiteManifest;
    }

    public function getValidationState(): int
    {
        return $this->validationState;
    }
}
