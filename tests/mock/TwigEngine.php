<?php

declare(strict_types=1);

namespace Mock;

use Stwarog\FuelFixturesGenerator\Renderer\Engine;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

final class TwigEngine implements Engine
{
    private Environment $twig;

    public function __construct(string $pathToTemplates)
    {
        $loader = new FilesystemLoader($pathToTemplates);
        $this->twig = new Environment($loader);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function render(string $fileName, array $params = []): string
    {
        return $this->twig->render($fileName, $params);
    }
}
