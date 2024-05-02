<?php

declare(strict_types=1);

namespace Spiral\Testing\Tests\Attribute;

use Spiral\Core\Internal\Introspector;
use Spiral\Storage\Config\StorageConfig;
use Spiral\Testing\Attribute\Config;
use Spiral\Testing\Attribute\TestScope;
use Spiral\Testing\Tests\TestCase;

final class ConfigTest extends TestCase
{
    public function testDefaultSettings(): void
    {
        $config = $this->getConfig(StorageConfig::CONFIG);
        $this->assertSame('uploads', $config['default']);
    }

    #[Config('storage.default', 'replaced')]
    public function testReplaceUsingAttribute(): void
    {
        $config = $this->getConfig(StorageConfig::CONFIG);
        $this->assertSame('replaced', $config['default']);
    }

    #[Config('storage.default', 'replaced')]
    #[Config('storage.servers.static.directory', 'test')]
    public function testMultipleAttributes(): void
    {
        $config = $this->getConfig(StorageConfig::CONFIG);
        $this->assertSame('replaced', $config['default']);
        $this->assertSame('test', $config['servers']['static']['directory']);
    }

    #[TestScope('foo')]
    #[Config('storage.default', 'replaced')]
    public function testReplaceUsingAttributeInScope(): void
    {
        $config = $this->getConfig(StorageConfig::CONFIG);
        $this->assertSame('replaced', $config['default']);
        $this->assertSame(['foo', 'root'], Introspector::scopeNames($this->getContainer()));
    }

    #[TestScope(['foo', 'bar'])]
    #[Config('storage.default', 'replaced')]
    public function testReplaceUsingAttributeInNestedScope(): void
    {
        $config = $this->getConfig(StorageConfig::CONFIG);
        $this->assertSame('replaced', $config['default']);
        $this->assertSame(['bar', 'foo', 'root'], Introspector::scopeNames($this->getContainer()));
    }
}
