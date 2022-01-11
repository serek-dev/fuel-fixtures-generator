<?php

declare(strict_types=1);

namespace Unit\Generator;

use Mock\ModelImitation;
use Orm\Model as BaseFuelOrm;
use PHPUnit\Framework\TestCase;
use Stwarog\FuelFixturesGenerator\Config;
use Stwarog\FuelFixturesGenerator\FixtureChunk;
use Stwarog\FuelFixturesGenerator\FixtureFactory;
use Stwarog\FuelFixturesGenerator\FuelAwareNameGenerator;
use Stwarog\FuelFixturesGenerator\FuelOrmModel as AdaptedModel;
use Stwarog\FuelFixturesGenerator\States\Callback;
use Stwarog\FuelFixturesGenerator\States\Reference;
use Stwarog\FuelFixturesGenerator\States\State;

/** @covers \Stwarog\FuelFixturesGenerator\FixtureFactory */
final class FixtureFactoryTest extends TestCase
{
    const NAMESPACE = 'Tests\Fixtures';

    public function testCreateShouldPopulateMandatoryProps(): FixtureChunk
    {
        // Given Factory and it's dependencies
        $config = $this->createStub(Config::class);
        $config->method('getNameSpace')->willReturn(self::NAMESPACE);
        $nameGenerator = new FuelAwareNameGenerator($config);

        $sut = new FixtureFactory($nameGenerator, $config);

        // And Model with ORM relations
        $fuelModel = $this->fakeNativeRelationsModel();

        // When Fixture is created
        $fixture = $sut->create(new AdaptedModel($fuelModel));

        // Then
        $this->assertNotEmpty($fixture->class);

        return $fixture;
    }

    private function fakeNativeRelationsModel(): BaseFuelOrm
    {
        return new class ($data = [], $new = true, $view = null, $cache = true) extends BaseFuelOrm {

            protected static $_table_name = 'some_table';

            protected static $_properties = [
                'id',
                'name',
                'currency_id'
            ];

            protected static array $_belongs_to = [
                'currency' => [
                    'model_to' => 'Model_Orm_Currency',
                    'key_to' => 'id',
                ],
            ];

            protected static array $_has_one = [
                'whitelabel_raffle' => [
                    'key_from' => 'id',
                    'model_to' => 'Model_Whitelabel_Orm_Raffle',
                ],
            ];

            protected static array $_has_many = [
                'rules' => [
                    'key_from' => 'id',
                    'model_to' => 'Model_Raffle_Orm_Rule',
                    'key_to' => 'raffle_id',
                ],
            ];
        };
    }

    public function testClassNameShouldBeShortNameFromNameResolver(): void
    {
        // Given Factory and it's dependencies
        $config = $this->createStub(Config::class);
        $config->method('getNameSpace')->willReturn(self::NAMESPACE);
        $nameGenerator = new FuelAwareNameGenerator($config);

        $sut = new FixtureFactory($nameGenerator, $config);

        // And Model with ORM relations
        $fuelModel = new ModelImitation();

        // When Fixture is created
        $fixture = $sut->create(new AdaptedModel($fuelModel));

        // Then namespace should be
        $this->assertSame('MockModelImitationFixture', $fixture->name);
    }

    /**
     * @depends testCreateShouldPopulateMandatoryProps
     * @param FixtureChunk $fixture
     */
    public function testNameSpaceShouldBeTakenFromConfiguration(FixtureChunk $fixture): void
    {
        // Then namespace should be
        $this->assertSame(self::NAMESPACE, $fixture->namespace);
    }

    /**
     * @depends testCreateShouldPopulateMandatoryProps
     * @param FixtureChunk $fixture
     */
    public function testPropertiesCantContainRelationIdFields(FixtureChunk $fixture): void
    {
        // Then fixture props should not contain relation fields
        foreach ($fixture->properties as $p) {
            $this->assertStringNotContainsStringIgnoringCase('id', $p);
        }

        // And basic state should be added
        $this->assertNotEmpty(array_filter($fixture->states, fn(State $s) => $s instanceof Callback));
    }

    /**
     * @depends testCreateShouldPopulateMandatoryProps
     * @param FixtureChunk $fixture
     */
    public function testStatesMustContainRelationIdFields(FixtureChunk $fixture): void
    {
        // Then states should be mapped to proper relations
        $currency = $fixture->states[1];
        $whitelabelRaffle = $fixture->states[2];
        $rules = $fixture->states[3];

        $this->assertInstanceOf(Reference::class, $currency);
        $this->assertSame('\\ModelOrmCurrencyFixture', $currency->targetFixture);
        $this->assertFalse($currency->hasMany);
        $this->assertInstanceOf(Reference::class, $whitelabelRaffle);
        $this->assertSame('\\ModelWhitelabelOrmRaffleFixture', $whitelabelRaffle->targetFixture);
        $this->assertFalse($whitelabelRaffle->hasMany);
        $this->assertInstanceOf(Reference::class, $rules);
        $this->assertSame('\\ModelRaffleOrmRuleFixture', $rules->targetFixture);
        $this->assertTrue($rules->hasMany);
    }
}
