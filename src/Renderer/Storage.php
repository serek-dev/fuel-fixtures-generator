<?php

namespace Stwarog\FuelFixturesGenerator\Renderer;

interface Storage
{
    public function save(string $path, string $content): void;
}
