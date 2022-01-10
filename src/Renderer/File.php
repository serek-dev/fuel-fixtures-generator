<?php

declare(strict_types=1);

namespace Stwarog\FuelFixturesGenerator\Renderer;

final class File implements Storage
{
    public function save(string $path, string $content): void
    {
        file_put_contents($path, $content);
        chmod($path, 0777);
    }
}
