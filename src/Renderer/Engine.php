<?php

declare(strict_types=1);

namespace Stwarog\FuelFixturesGenerator\Renderer;

interface Engine
{
    public function render(string $fileName, array $params = []): string;
}
