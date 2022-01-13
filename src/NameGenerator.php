<?php

declare(strict_types=1);

namespace Stwarog\FuelFixturesGenerator;

/**
 *  This class generates new Fixture class name basing on Fuel`s related model name.
 *  E.g: relation can be Orm_Model_Currency, and we want to generate fixture class named OrmModelCurrencyFixture.
 *
 *  Each method value should start from "\"
 */
interface NameGenerator
{
    /**
     * Should attach namespace to newly generated short name
     */
    public function getFullName(string $class): string;

    /**
     *  Should contain simple class name without namespace
     */
    public function getShortName(string $class): string;
}
