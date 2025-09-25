<?php

declare(strict_types=1);

namespace Tests\Doubles\Traits;

use ReflectionClass;
use ReflectionMethod;

trait InitializesCallCounts
{
    protected function initializeMethodCallCount(): array
    {
        $reflection = new ReflectionClass($this);
        $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

        $callCount = [];
        foreach ($methods as $method) {
            // Skip magic methods and constructor
            if (!str_starts_with($method->getName(), '__')) {
                $callCount[$method->getName()] = 0;
            }
        }

        return $callCount;
    }
}
