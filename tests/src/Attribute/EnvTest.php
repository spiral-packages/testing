<?php

declare(strict_types=1);

namespace Spiral\Testing\Tests\Attribute;

use Spiral\Core\Internal\Introspector;
use Spiral\Testing\Attribute\Env;
use Spiral\Testing\Attribute\TestScope;
use Spiral\Testing\Tests\TestCase;

final class EnvTest extends TestCase
{
    public const ENV = [
        'FOO' => 'BAR',
        'BAZ' => 'QUX'
    ];

    public function testDefaultEnv(): void
    {
        $this->assertEnvironmentValueSame('FOO', 'BAR');
        $this->assertEnvironmentValueSame('BAZ', 'QUX');
    }

    #[Env('FOO', 'BAZ')]
    public function testEnvFromAttribute(): void
    {
        $this->assertEnvironmentValueSame('FOO', 'BAZ');
        $this->assertEnvironmentValueSame('BAZ', 'QUX');
    }

    #[Env('FOO', 'BAZ')]
    #[Env('BAZ', 'BAZ')]
    public function testMultipleAttributes(): void
    {
        $this->assertEnvironmentValueSame('FOO', 'BAZ');
        $this->assertEnvironmentValueSame('BAZ', 'BAZ');
    }

    #[TestScope('foo')]
    #[Env('FOO', 'BAZ')]
    public function testEnvFromAttributeInScope(): void
    {
        $this->assertEnvironmentValueSame('FOO', 'BAZ');
        $this->assertEnvironmentValueSame('BAZ', 'QUX');
        $this->assertSame(['foo', 'root'], Introspector::scopeNames($this->getContainer()));
    }

    #[TestScope(['foo', 'bar'])]
    #[Env('FOO', 'BAZ')]
    public function testEnvFromAttributeInNestedScope(): void
    {
        $this->assertEnvironmentValueSame('FOO', 'BAZ');
        $this->assertEnvironmentValueSame('BAZ', 'QUX');
        $this->assertSame(['bar', 'foo', 'root'], Introspector::scopeNames($this->getContainer()));
    }
}
