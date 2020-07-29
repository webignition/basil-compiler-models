<?php

declare(strict_types=1);

namespace webignition\BasilCompilerModels\Tests\Unit;

use PHPUnit\Framework\TestCase;
use webignition\BasilCompilerModels\TestManifest;
use webignition\BasilModels\Test\Configuration as TestConfiguration;
use webignition\BasilModels\Test\ConfigurationInterface as TestConfigurationInterface;

class TestManifestTest extends TestCase
{
    private const SOURCE = 'test.yml';
    private const TARGET = 'GeneratedTest.php';

    private TestManifest $manifest;
    private TestConfigurationInterface $configuration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->configuration = new TestConfiguration('chrome', 'http://example.com');
        $this->manifest = new TestManifest($this->configuration, self::SOURCE, self::TARGET);
    }

    public function testGetTarget()
    {
        self::assertSame(self::TARGET, $this->manifest->getTarget());
    }

    public function testGetData()
    {
        self::assertSame(
            [
                'config' => [
                    'browser' => 'chrome',
                    'url' => 'http://example.com',
                ],
                'source' => self::SOURCE,
                'target' => self::TARGET,
            ],
            $this->manifest->getData()
        );
    }

    public function testFromArray()
    {
        self::assertEquals(
            new TestManifest($this->configuration, self::SOURCE, self::TARGET),
            TestManifest::fromArray([
                'config' => [
                    'browser' => 'chrome',
                    'url' => 'http://example.com',
                ],
                'source' => self::SOURCE,
                'target' => self::TARGET,
            ])
        );
    }
}
