<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels;

class SuiteManifestFactory
{
    /**
     * @param array<mixed> $data
     * @return SuiteManifest|null
     *
     * @throws InvalidSuiteManifestException
     */
    public function createFromArray(array $data): ?SuiteManifest
    {
        $manifest = SuiteManifest::fromArray($data);
        $validationState = $manifest->validate();
        if (SuiteManifest::VALIDATION_STATE_VALID !== $validationState) {
            throw new InvalidSuiteManifestException($manifest, $validationState);
        }

        return $manifest;
    }
}
