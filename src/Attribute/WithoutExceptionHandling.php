<?php

declare(strict_types=1);

namespace Spiral\Testing\Attribute;

#[\Attribute(flags: \Attribute::TARGET_METHOD)]
final class WithoutExceptionHandling {}
