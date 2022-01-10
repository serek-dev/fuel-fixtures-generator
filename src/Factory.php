<?php

namespace Stwarog\FuelFixturesGenerator;

interface Factory
{
    public function create(Model $adapted): FixtureChunk;
}
