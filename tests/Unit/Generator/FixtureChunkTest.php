<?php

declare(strict_types=1);

namespace Unit\Generator;

use PHPUnit\Framework\TestCase;
use Stwarog\FuelFixturesGenerator\FixtureChunk;
use Stwarog\FuelFixturesGenerator\States\Callback;
use Stwarog\FuelFixturesGenerator\States\Reference;

/** @covers \Stwarog\FuelFixturesGenerator\FixtureChunk */
final class FixtureChunkTest extends TestCase
{
    /** @test */
    public function getNestedFixtures_HasRelations_ShouldReturnsNewlyCreatedFixtureNames(): void
    {
        // Given Chunk with some states
        $sut = new FixtureChunk();
        $sut->addState(new Callback('callback', 'callback'));
        $sut->addState(new Reference('reference', 'reference', 'FIXTURE_NAME', false));
        $sut->addState(new Reference('reference2', 'reference2', 'FIXTURE_NAME_2', false));

        // When getNestedFixturesCalled
        $actual = $sut->getNestedFixtures();
        $expected = ['FIXTURE_NAME', 'FIXTURE_NAME_2'];

        // Then result should be as expected
        $this->assertSame($expected, $actual);
    }
}
