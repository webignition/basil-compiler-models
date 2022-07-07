<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Factory;

use webignition\BasilCompilerModels\Exception\InvalidTestManifestException;
use webignition\BasilCompilerModels\Model\TestManifest;

class TestManifestFactory implements TestManifestFactoryInterface
{
    /**
     * @param array<mixed> $data
     *
     * @throws InvalidTestManifestException
     */
    public function create(array $data): TestManifest
    {
        $configData = $data['config'] ?? [];
        $configData = is_array($configData) ? $configData : [];

        $browser = $configData['browser'] ?? '';
        $browser = is_string($browser) ? trim($browser) : '';
        if ('' === $browser) {
            throw InvalidTestManifestException::createForEmptyBrowser();
        }

        $url = $configData['url'] ?? '';
        $url = is_string($url) ? trim($url) : '';
        if ('' === $url) {
            throw InvalidTestManifestException::createForEmptyUrl();
        }

        $source = $data['source'] ?? '';
        $source = is_string($source) ? trim($source) : '';
        if ('' === $source) {
            throw InvalidTestManifestException::createForEmptySource();
        }

        $target = $data['target'] ?? '';
        $target = is_string($target) ? trim($target) : '';
        if ('' === $target) {
            throw InvalidTestManifestException::createForEmptyTarget();
        }

        $stepNames = $data['step_names'] ?? [];
        $stepNames = is_array($stepNames) ? $stepNames : [];

        $filteredStepNames = [];
        foreach ($stepNames as $stepName) {
            if (is_string($stepName) && '' !== $stepName) {
                $filteredStepNames[] = $stepName;
            }
        }

        return new TestManifest($browser, $url, $source, $target, $filteredStepNames);
    }
}
