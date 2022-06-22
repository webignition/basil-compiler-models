<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Factory;

use webignition\BasilCompilerModels\Model\TestManifest;

class TestManifestFactory
{
    /**
     * @param array<mixed> $data
     */
    public function create(array $data): TestManifest
    {
        $configData = $data['config'] ?? [];
        $configData = is_array($configData) ? $configData : [];

        $source = $data['source'] ?? '';
        $source = is_string($source) ? $source : '';

        $target = $data['target'] ?? '';
        $target = is_string($target) ? $target : '';

        $stepNames = $data['step_names'] ?? [];
        $stepNames = is_array($stepNames) ? $stepNames : [];

        $filteredStepNames = [];
        foreach ($stepNames as $stepName) {
            if (is_string($stepName) && '' !== $stepName) {
                $filteredStepNames[] = $stepName;
            }
        }

        return new TestManifest(
            $configData['browser'] ?? '',
            $configData['url'] ?? '',
            $source,
            $target,
            $filteredStepNames
        );
    }
}
