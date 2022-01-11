<?php

declare(strict_types=1);

namespace Stwarog\FuelFixturesGenerator;

use Stwarog\FuelFixturesGenerator\States\Reference;
use Stwarog\FuelFixturesGenerator\States\State;

/** @internal */
final class FixtureChunk
{
    public string $namespace;

    /** @var array<string> */
    public array $properties;

    /** @var array<State> */
    public array $states = [];

    public string $class;

    public string $name;

    public function addState(State $state): void
    {
        $this->states[] = $state;
    }

    /** @return array<string> */
    public function getNestedFixtures(): array
    {
        $mapped = array_map(fn(State $s) => $s instanceof Reference ? $s->targetFixture : null, $this->states);
        $filtered = array_filter($mapped);
        return array_values($filtered);
    }
}
