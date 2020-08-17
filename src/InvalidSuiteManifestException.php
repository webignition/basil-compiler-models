<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels;

class InvalidSuiteManifestException extends \Exception
{
    private SuiteManifest $suiteManifest;
    private int $validationState;

    public function __construct(SuiteManifest $suiteManifest, int $validationState)
    {
        parent::__construct(sprintf(
            'Invalid suite manifest. Validation state %s',
            $validationState
        ));

        $this->suiteManifest = $suiteManifest;
        $this->validationState = $validationState;
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
