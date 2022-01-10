<?php

declare(strict_types=1);

namespace Stwarog\FuelFixturesGenerator\States;

/** @internal */
final class Callback implements State
{
    public string $name;
    public string $methodName;

    public function __construct(string $name, string $methodName)
    {
        $this->name = $name;
        $this->methodName = $methodName;
    }

    public function type(): string
    {
        return 'callback';
    }
}
