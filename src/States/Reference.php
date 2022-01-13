<?php

declare(strict_types=1);

namespace Stwarog\FuelFixturesGenerator\States;

/** @internal */
final class Reference implements State
{
    public string $name;
    public string $property;
    public string $targetFixture;
    public bool $hasMany;
    public string $targetModel;

    public function __construct(
        string $name,
        string $property,
        string $targetFixture,
        bool $hasMany,
        string $targetModel
    ) {
        $this->name = $name;
        $this->property = $property;
        $this->targetFixture = $targetFixture;
        $this->hasMany = $hasMany;
        $this->targetModel = $targetModel;
    }

    public function type(): string
    {
        return 'reference';
    }
}
