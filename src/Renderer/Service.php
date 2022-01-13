<?php

declare(strict_types=1);

namespace Stwarog\FuelFixturesGenerator\Renderer;

use Stwarog\FuelFixturesGenerator\Config;
use Stwarog\FuelFixturesGenerator\Factory as Chunk;
use Stwarog\FuelFixturesGenerator\FuelOrmModel as AdapterOf;

use const PHP_EOL;

final class Service
{
    private Chunk $factory;
    private Engine $engine;
    private Storage $storage;
    private Config $config;

    public function __construct(Chunk $factory, Engine $engine, Storage $storage, Config $config)
    {
        $this->factory = $factory;
        $this->engine = $engine;
        $this->storage = $storage;
        $this->config = $config;
    }

    /**
     * @param string $fuelOrmModel
     * @return array('created' => non-empty-string, 'nested_fixtures' => array<int, array<string, string>>
     */
    public function generate(string $fuelOrmModel): array
    {
        $chunk = $this->factory->create(new AdapterOf(new $fuelOrmModel()));

        $output = $this->engine->render($this->config->outputTemplate(), compact('chunk'));

        $this->storage->save(
            $fullPath = $this->config->storagePath() . $chunk->name . '.php',
            '<?php' . PHP_EOL . $output
        );

        return [
            'created' => $fullPath,
            'nested_fixtures' => $chunk->getNestedFixtures(),
        ];
    }
}
