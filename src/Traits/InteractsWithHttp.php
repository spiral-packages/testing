<?php

declare(strict_types=1);

namespace Spiral\Testing\Traits;

use Spiral\Core\FactoryInterface;
use Spiral\Testing\Http\FakeHttp;
use Spiral\Testing\Http\FileFactory;

trait InteractsWithHttp
{
    final public function getFileFactory(): FileFactory
    {
        return new FileFactory();
    }

    final public function fakeHttp(): FakeHttp
    {
        return $this->getContainer()->get(FactoryInterface::class)->make(FakeHttp::class, [
            'fileFactory' => $this->getFileFactory(),
            'scope' => function (\Closure $closure, array $bindings = []) {
                return self::runScopes(['http'], $closure, $this->getContainer(), $bindings);
            }
        ]);
    }
}
