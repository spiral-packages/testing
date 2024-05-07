<?php

declare(strict_types=1);

namespace Spiral\Testing\Tests\Attribute;

use Spiral\Core\Internal\Introspector;
use Spiral\Testing\Attribute\TestScope;
use Spiral\Testing\Tests\TestCase;

final class TestScopeTest extends TestCase
{
    public function testDefaultScope(): void
    {
        $this->assertSame(['root'], Introspector::scopeNames($this->getContainer()));
    }

    #[TestScope('foo')]
    public function testScopeFromAttribute(): void
    {
        $this->assertSame(['foo', 'root'], Introspector::scopeNames($this->getContainer()));
    }

    #[TestScope(['foo', 'bar'])]
    public function testNestedScopes(): void
    {
        $this->assertSame(['bar', 'foo', 'root'], Introspector::scopeNames($this->getContainer()));
    }

    #[TestScope('foo', ['test' => \stdClass::class])]
    public function testScopeWithBindings(): void
    {
        $this->assertSame(['foo', 'root'], Introspector::scopeNames($this->getContainer()));
        $this->assertInstanceOf(\stdClass::class, $this->getContainer()->get('test'));
    }
}
