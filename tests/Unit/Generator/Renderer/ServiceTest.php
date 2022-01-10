<?php

declare(strict_types=1);

namespace Unit\Generator\Renderer;

use Mock\ModelImitation;
use PHPUnit\Framework\TestCase;
use Stwarog\FuelFixturesGenerator\Config;
use Stwarog\FuelFixturesGenerator\Factory;
use Stwarog\FuelFixturesGenerator\FixtureChunk;
use Stwarog\FuelFixturesGenerator\Renderer\Engine;
use Stwarog\FuelFixturesGenerator\Renderer\Service;
use Stwarog\FuelFixturesGenerator\Renderer\Storage;
use Stwarog\FuelFixturesGenerator\States\Callback;
use Stwarog\FuelFixturesGenerator\States\Reference;

/** @covers \Stwarog\FuelFixturesGenerator\Renderer\Service */
final class ServiceTest extends TestCase
{
    /** @test */
    public function generate(): void
    {
        // Given dependencies
        $config = $this->createStub(Config::class);
        $config->method('outputTemplate')->willReturn('outputTemplate/');
        $config->method('storagePath')->willReturn('storagePath/');

        // And FixtureChunk that should be created by Factory
        $chunk = new FixtureChunk();
        $chunk->name = 'chunk_name';
        $chunk->addState(new Callback('callback', 'callback'));
        $chunk->addState(new Reference('reference', 'reference', 'FIXTURE_NAME', false));
        $chunk->addState(new Reference('reference2', 'reference2', 'FIXTURE_NAME_2', false));

        $factory = $this->createStub(Factory::class);
        $factory->method('create')->willReturn($chunk);

        // And output that should be created by the Engine (for example Twig)
        $output = 'some output';
        $engine = $this->createMock(Engine::class);
        $engine
            ->expects($this->once())
            ->method('render')
            ->with('outputTemplate/', ['chunk' => $chunk])
            ->willReturn($output);

        // And path that should be taken from the storage config key
        $storage = $this->createMock(Storage::class);
        $storage
            ->expects($this->once())
            ->method('save')
            ->with('storagePath/chunk_name.php', '<?php' . PHP_EOL . 'some output');

        $sut = new Service($factory, $engine, $storage, $config);

        // When generated
        $actual = $sut->generate(ModelImitation::class);

        // Then output should contain process details
        $expected = [
            'created' => 'storagePath/chunk_name.php',
            'nested_fixtures' => [
                'FIXTURE_NAME',
                'FIXTURE_NAME_2',
            ]
        ];
        $this->assertSame($expected, $actual);
    }
}
