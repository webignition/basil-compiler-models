<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Tests\Unit;

use Mockery;
use phpmock\mockery\PHPMockery;
use PHPUnit\Framework\TestCase;
use webignition\BasilCompilerModels\Configuration;
use webignition\BasilCompilerModels\InvalidSuiteManifestException;
use webignition\BasilCompilerModels\SuiteManifest;
use webignition\BasilCompilerModels\SuiteManifestFactory;

class SuiteManifestFactoryTest extends TestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    private SuiteManifestFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new SuiteManifestFactory();
    }

    public function testCreateFromArrayThrowsException(): void
    {
        try {
            $this->factory->createFromArray([]);
            $this->fail('InvalidSuiteManifestException not thrown');
        } catch (InvalidSuiteManifestException $invalidSuiteManifestException) {
            self::assertEquals(
                new SuiteManifest(
                    new Configuration('', '', ''),
                    []
                ),
                $invalidSuiteManifestException->getSuiteManifest()
            );

            self::assertSame(
                SuiteManifest::VALIDATION_STATE_CONFIGURATION_INVALID,
                $invalidSuiteManifestException->getValidationState()
            );
        }
    }

    public function testCreateFromArray(): void
    {
        $source = '/source';
        $target = '/target';
        $baseClass = 'BaseClass';

        $mockNamespace = 'webignition\BasilCompilerModels';

        PHPMockery::mock($mockNamespace, 'is_readable')
            ->with($source)
            ->andReturnTrue();

        PHPMockery::mock($mockNamespace, 'is_dir')
            ->with($target)
            ->andReturnTrue();

        PHPMockery::mock($mockNamespace, 'is_writable')
            ->with($target)
            ->andReturnTrue();

        $data = [
            'config' => [
                'source' => $source,
                'target' => $target,
                'base-class' => $baseClass,
            ],
        ];

        $suiteManifest = $this->factory->createFromArray($data);

        self::assertEquals(
            new SuiteManifest(
                new Configuration($source, $target, $baseClass)
            ),
            $suiteManifest
        );
    }
}
