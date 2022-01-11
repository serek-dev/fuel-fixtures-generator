# Fuel fixture generator

This is a package that helps to generate [Fuel Fixture package](https://github.com/stwarog/fuel-fixtures)
implementation.

## Development

```bash
make start # starts the local env
make check # calls all tests including statis analysis
  make cs
  make phpstan
  make unit
  make integration
```

## Usage

```php
# The best approach is using some DI container implementation and fetch the service from there:

use Stwarog\FuelFixturesGenerator\{Config as FixtureGeneratorConfig,
    Factory as FixtureChunkFactory,
    FixtureFactory,
    FuelAwareNameGenerator,
    NameGenerator,
    Renderer\Engine,
    Renderer\Engine as FixtureGeneratorViewEngine,
    Renderer\File,
    Renderer\Service as Generator,
    Renderer\Storage};

# e.g. entry configuration

return [
    NameGenerator::class => fn(Container $c) => new FuelAwareNameGenerator($c->get(FixtureGeneratorConfig::class)),

    Storage::class => fn(Container $c) => new File(),

    FixtureChunkFactory::class => fn(Container $c) => new FixtureFactory(
        $c->get(NameGenerator::class),
        $c->get(FixtureGeneratorConfig::class)
    ),

    // Adapter for Twig of Fixture Generator Engine
    FixtureGeneratorViewEngine::class => function (Container $c): Engine {
        return new class ($c->get(TwigViewRenderer::class)) implements FixtureGeneratorViewEngine {

            private ViewRendererContract $renderer;

            public function __construct(TwigViewRenderer $renderer)
            {
                $this->renderer = $renderer;
            }

            public function render(string $fileName, array $params = []): string
            {
                return $this->renderer->render('fixture.twig', $params);
            }
        };
    },

    FixtureGeneratorConfig::class => function (Container $c): FixtureGeneratorConfig {
        return new class () implements FixtureGeneratorConfig {

            public function getNameSpace(): string
            {
                return 'Tests\Fixtures';
            }

            public function storagePath(): string
            {
                return APPPATH . 'tests/fixtures/';
            }

            public function outputTemplate(): string
            {
                return 'fixture.twig';
            }
        };
    },

    Generator::class => fn(Container $c) => new Generator(
        $c->get(FixtureChunkFactory::class),
        $c->get(FixtureGeneratorViewEngine::class),
        $c->get(Storage::class),
        $c->get(FixtureGeneratorConfig::class),
    ),
];
```

### Important

This package needs some Templating Engine. In integration tests can be found an example with Twig.

There is no implementation of usage in CLI as Fuel autoload system is really challenging. Fetch Service and call method
to generate the file.


