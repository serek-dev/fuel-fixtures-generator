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
        $sut->addState(
            new Reference('reference', 'reference', 'FIXTURE_NAME', false, 'target_model1')
        );
        $sut->addState(
            new Reference('reference2', 'reference2', 'FIXTURE_NAME_2', false, 'target_model1')
        );

        // When getNestedFixturesCalled
        $actual = $sut->getNestedFixtures();
        $expected = [
            [
                'target_fixture' => 'FIXTURE_NAME',
                'target_model' => 'target_model1',
            ],
            [
                'target_fixture' => 'FIXTURE_NAME_2',
                'target_model' => 'target_model1',
            ]
        ];

        // Then result should be as expected
        $this->assertSame($expected, $actual);
    }
}
