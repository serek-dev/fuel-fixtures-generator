<?php

namespace Stwarog\FuelFixturesGenerator;

interface Model
{
    public function getClass(): string;

    public function hasRelations(): bool;

    /** @return array<string> */
    public function getProperties(): array;

    /** @return array<string, array<string, mixed>> - property => related model full class name */
    public function getRelations(): array;
}
