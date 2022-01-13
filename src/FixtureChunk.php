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

    /** @return array<int, array<string, string>> */
    public function getNestedFixtures(): array
    {
        $mapped = array_map(function (State $s) {
            return $s instanceof Reference ? [
                'target_fixture' => $s->targetFixture,
                'target_model' => $s->targetModel,
            ] : null;
        }, $this->states);
        $filtered = array_filter($mapped);
        return array_values($filtered);
    }
}
