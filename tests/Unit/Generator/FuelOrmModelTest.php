<?php

declare(strict_types=1);

namespace Unit\Generator;

use Orm\Model as BaseFuelOrm;
use PHPUnit\Framework\TestCase;
use Stwarog\FuelFixturesGenerator\FuelOrmModel as AdaptedModel;

/** @covers \Stwarog\FuelFixturesGenerator\FuelOrmModel */
final class FuelOrmModelTest extends TestCase
{
    public function testAdaptedBaseFuelOrmWithoutRelationsShouldReturnsExpectedFields(): void
    {
        // Given Orm Model (our abstract, not Fuel/Core)
        $expectedProps = ['id', 'name', 'something_else', 'field_with_id'];
        $nativeModel = $this->fakeNativeFuelModel($expectedProps);

        // When Adapted
        $Adapted = new AdaptedModel($nativeModel);

        // Then
        $actualProps = $Adapted->getProperties();

        # _properties
        $this->assertSame($expectedProps, $actualProps);

        # class name
        $this->assertSame(get_class($nativeModel), $Adapted->getClass());

        # has relations = false
        $this->assertFalse($Adapted->hasRelations());
        $this->assertEmpty($Adapted->getRelations());
    }

    public function fakeNativeFuelModel(array $data = []): BaseFuelOrm
    {
        return new class ($data, $new = true, $view = null, $cache = false) extends BaseFuelOrm {

            protected static $_table_name = 'some_table_that_does_not_exist';

            protected static $_properties = [
                'id',
                'name',
                'something_else',
                'field_with_id',
            ];
        };
    }

    public function testAdaptedBaseFuelOrmWithRelationsInNativeWayShouldReturnsExpectedRelationFields(): void
    {
        // Given Orm Model (our abstract, not Fuel/Core)
        // with relation written in native way (stat props)
        $nativeModel = $this->fakeNativeRelationsModel();

        // When Adapted
        $adapted = new AdaptedModel($nativeModel);

        // Then it should contain relations
        $this->assertTrue($adapted->hasRelations());
        $expectedRelations = [
            "currency" => [
                'target' => "Model_Orm_Currency",
                'has_many' => false,
            ],
            "whitelabel_raffle" => [
                'target' => "Model_Whitelabel_Orm_Raffle",
                'has_many' => false,
            ],
            "rules" => [
                'target' => "Models\Model_Raffle_Orm_Rule",
                'has_many' => true,
            ],
        ];
        $this->assertSame($expectedRelations, $adapted->getRelations());
    }

    private function fakeNativeRelationsModel(array $data = []): BaseFuelOrm
    {
        return new class ($data, $new = true, $view = null, $cache = false) extends BaseFuelOrm {

            protected static $_table_name = 'some_table';

            protected static $_properties = [
                'id',
                'name',
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
                    'model_to' => 'Models\Model_Raffle_Orm_Rule',
                    'key_to' => 'raffle_id',
                ],
            ];
        };
    }
}
