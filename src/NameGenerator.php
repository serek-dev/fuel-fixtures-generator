<?php

declare(strict_types=1);

namespace Stwarog\FuelFixturesGenerator;

/**
 *  This class generates new Fixture class name basing on Fuel`s related model name.
 *  E.g: relation can be Orm_Model_Currency, and we want to generate fixture class named Orm_Model_Currency_Fixture
 */
interface NameGenerator
{
    public function getFullName(string $class): string;

    public function getShortName(string $class): string;
}
